@props(['uid' => str()->random()])
<div id="{{ $uid }}">
    <x-select x-init="$nextTick(() => initWorld())" :options="$options" {{ $attributes }} />
    <script>
        (() => {
            const el = (parent = window.document) => parent.querySelector('[id="{{ $uid }}"]');

            const overwrite = () => {
                const data = Alpine.$data(el().firstElementChild);

                data.initWorld = function() {
                    // Compatibility with Casting
                    if (typeof this.wireModel === typeof {}) {
                        this.select({
                            value: this.wireModel.id
                        });
                    }
                };
            };

            document.addEventListener("DOMContentLoaded", () => {
                Wireui.hook('load', overwrite);
                Livewire.hook('message.processed', (message, component) => {
                    if (el(component.el)) {
                        overwrite();
                    }
                });
            });
        })()
    </script>
</div>
