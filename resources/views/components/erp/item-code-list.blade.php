@props([
    'options' => [],
    'listId' => 'be-item-codes',
])

@php
    /** @var list<array{code: string, label: string}> $options */
@endphp

<datalist id="{{ $listId }}">
    @foreach ($options as $option)
        <option value="{{ $option['code'] }}">{{ $option['label'] }}</option>
    @endforeach
</datalist>
