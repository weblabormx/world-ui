@props(['alwaysFetch' => true])
@component('worldui::components.select-wrapper', [
    'uid' => $attributes->get('wire:key', \Str::random()),
    'regex' => $attributes->get('regex'),
])
    <x-select option-label="name" option-value="id"
        :async-data="isset($api)
            ? [
                'api' => config('worldui.endpoint') . $api,
                'method' => 'GET',
                'params' => [],
                'alwaysFetch' => $alwaysFetch,
            ]
            : null"
        :options="isset($api) ? null : []"
        :always-fetch="$alwaysFetch"
        x-init="$nextTick(() => {
            fetchOptions();
            const timeout = () => setTimeout(() => {
                console.info('Syncing world input');
                if (!asyncData.fetching) {
                    syncSelectedFromWireModel();
                    return;
                }
                timeout();
            }, 500);
            timeout();
        });
        
        $watch('displayOptions', (v) => {
            const regex = @toJs(addslashes($attributes->get('regex')));
        
            if (!regex) {
                return;
            }
        
            v = Alpine.raw(v);
        
            v.map((d) => {
                d.label = (new RegExp(regex).exec(d.label) ?? [])[0] ?? d.label;
                return d;
            })
        
            displayOptions = v;
        })"
        {{ $attributes }}>
        @isset($beforeOptions)
            <x-slot name="beforeOptions">
                {{ $beforeOptions }}
            </x-slot>
        @endisset

        @isset($afterOptions)
            <x-slot name="afterOptions">
                {{ $afterOptions }}
            </x-slot>
        @endisset
    </x-select>
@endcomponent
