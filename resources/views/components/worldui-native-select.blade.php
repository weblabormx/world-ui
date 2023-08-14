<x-native-select option-label="name" option-value="id"
    :options="isset($options) ? $options : []"
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
</x-native-select>
