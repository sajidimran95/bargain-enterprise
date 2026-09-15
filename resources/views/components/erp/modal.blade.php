@props([
    'show' => false,
    'title' => null,
    'maxWidth' => 'lg',
])

@php
    $width = match ($maxWidth) {
        'sm' => 'be-modal__panel--sm',
        'md' => 'be-modal__panel--md',
        'xl' => 'be-modal__panel--xl',
        'full' => 'be-modal__panel--full',
        default => 'be-modal__panel--lg',
    };
@endphp

<div
    x-data="{ open: @js($show) }"
    x-show="open"
    x-cloak
    x-on:be-close-modal.window="open = false"
    x-on:keydown.escape.window="open = false"
    class="be-modal"
    role="dialog"
    aria-modal="true"
    {{ $attributes }}
>
    <div class="be-modal__backdrop" x-on:click="open = false"></div>
    <div class="be-modal__panel {{ $width }}" @click.stop>
        @if ($title || isset($header))
            <div class="be-modal__header">
                <h2 class="be-modal__title">{{ $title ?? $header }}</h2>
                <button type="button" class="be-modal__close" x-on:click="open = false" aria-label="Close">&times;</button>
            </div>
        @endif
        <div class="be-modal__body">
            {{ $slot }}
        </div>
        @isset($footer)
            <div class="be-modal__footer">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
