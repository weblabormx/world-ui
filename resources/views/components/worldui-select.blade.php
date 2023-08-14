@props(['alwaysFetch' => false])
@component('worldui::components.select-wrapper', [
    'uid' => $attributes->get('wire:key', \Str::random()),
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
