@props([
    'uid' => \Str::random(),
])
<div id="{{ $uid }}">

    {{ $slot }}

    <script>
        (() => {
            const overwrite = () => {
                const el = document.querySelector('[id="{{ $uid }}"]>div');
                const data = Alpine.$data(el);

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
