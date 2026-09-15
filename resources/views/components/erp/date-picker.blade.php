@props([
    'disabled' => false,
])

<input
    type="date"
    {{ $attributes->merge(['class' => 'be-input'])->class(['opacity-60' => $disabled]) }}
    @disabled($disabled)
>
