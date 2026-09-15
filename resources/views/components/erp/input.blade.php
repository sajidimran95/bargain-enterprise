@props([
    'disabled' => false,
])

<input
    {{ $attributes->merge(['class' => 'be-input'])->class(['opacity-60' => $disabled]) }}
    @disabled($disabled)
>
