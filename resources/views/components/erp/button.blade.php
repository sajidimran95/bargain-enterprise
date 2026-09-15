@props([
    'variant' => 'default',
    'type' => 'button',
    'disabled' => false,
])

@php
    $classes = match ($variant) {
        'primary' => 'be-btn be-btn--primary',
        'danger' => 'be-btn be-btn--danger',
        default => 'be-btn',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->class([$classes]) }}
    @disabled($disabled)
>
    {{ $slot }}
</button>
