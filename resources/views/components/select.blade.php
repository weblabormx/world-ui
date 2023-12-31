@props(['uid' => str()->random()])
<div id="{{ $uid }}">

    <x-dynamic-component
        :component="WireUi::component('select')"
        :options="$getOptions()"
        x-init="$nextTick(() => initWorld())"
        optionLabel="name"
        optionValue="id"
        {{ $attributes }} />


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
