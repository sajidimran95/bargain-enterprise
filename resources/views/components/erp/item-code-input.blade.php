@props([
    'placeholder' => 'Code…',
])

<input
    type="text"
    placeholder="{{ $placeholder }}"
    autocomplete="off"
    spellcheck="false"
    {{ $attributes->class(['be-input', 'be-input--bare', 'be-input--grid', 'font-mono', 'be-item-code__input']) }}
>
