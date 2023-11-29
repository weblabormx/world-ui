@props([
    'uid' => \Str::random(),
    'regex' => null,
])

<div id="{{ $uid }}" data-regex="@toJs($regex)">

    {{ $slot }}

    <script>
        (() => {
            const overwrite = () => {
                const el = document.querySelector('[id="{{ $uid }}"]>div');
                const data = Alpine.$data(el);

                data.regex = el.parentElement.dataset?.regex?.slice(1, -1);
                data.initWorld = function() {
                    this.$nextTick(() => {
                        this.fetchOptions();

                        let tries = 0;
                        const timeout = () => setTimeout(() => {
                            tries++;
                            console.info('Syncing world input');
                            if (!this.asyncData.fetching) {
                                this.syncSelectedFromWireModel();
                                return;
                            }

                            if (tries > 10) {
                                return;
                            }
                            timeout();
                        }, 500);

                        timeout();
                    });

                    this.$watch('options', (value) => {
                        this.$nextTick(() => {
                            if (!this.regex) {
                                return;
                            }

                            let newOptions = Alpine.raw(this.displayOptions);

                            newOptions = value.map((d) => {
                                d = Alpine.raw(d);
                                d.label = (new RegExp(this.regex).exec(d.label) ?? [])[0] ?? d.label;
                                return d;
                            });

                            this.displayOptions = newOptions;
                        })
                    })
                };

                data.makeRequest = function(params = {}) {
                    const {
                        api,
                        method,
                        credentials
                    } = this.asyncData

                    const url = new URL(api ?? '')

                    const parameters = Object.assign(
                        params,
                        window.Alpine.raw(this.asyncData.params),
                        ...Array.from(url.searchParams).map(([key, value]) => ({
                            [key]: value
                        }))
                    )

                    url.search = ''

                    if (method === 'GET') {
                        url.search = '?' + new URLSearchParams(parameters).toString()
                    }

                    const request = new Request(url, {
                        method,
                        body: method === 'POST' ? JSON.stringify(parameters) : undefined,
                        credentials
                    })

                    request.headers.set('Content-Type', 'application/json')
                    request.headers.set('Accept', 'application/json')
                    request.headers.set('X-Requested-With', 'XMLHttpRequest')
                    request.headers.set('Authorization', "Bearer {{ config('worldui.api_token') }}")

                    const csrfToken = document.head.querySelector('[name="csrf-token"]')?.getAttribute(
                        'content')

                    if (csrfToken) {
                        request.headers.set('X-CSRF-TOKEN', csrfToken)
                    }

                    return request
                };
            };

            document.addEventListener("DOMContentLoaded", () => {
                Wireui.hook('load', overwrite);
                Livewire.hook('message.processed', (message, component) => {
                    if (component.el.querySelector('[id="{{ $uid }}"]')) {
                        overwrite();
                    }
                });
            });
        })()
    </script>
</div>
