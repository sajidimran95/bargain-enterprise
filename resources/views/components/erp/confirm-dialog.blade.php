@props([
    'show' => false,
    'title' => 'Confirm',
    'confirmLabel' => 'OK',
    'cancelLabel' => 'Cancel',
])

<div
    x-data="{ open: @js($show) }"
    x-show="open"
    x-cloak
    x-on:be-close-modal.window="open = false"
    class="be-modal"
    role="alertdialog"
    aria-modal="true"
>
    <div class="be-modal__backdrop" x-on:click="open = false"></div>
    <div class="be-modal__panel be-modal__panel--sm" @click.stop>
        <div class="be-modal__header">
            <h2 class="be-modal__title">{{ $title }}</h2>
        </div>
        <div class="be-modal__body">
            {{ $slot }}
        </div>
        <div class="be-modal__footer">
            <button type="button" class="be-btn" x-on:click="open = false">{{ $cancelLabel }}</button>
            @isset($confirm)
                {{ $confirm }}
            @endisset
        </div>
    </div>
</div>
