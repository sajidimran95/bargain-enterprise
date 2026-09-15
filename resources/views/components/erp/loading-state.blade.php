@props([
    'message' => 'Loading…',
])

<div {{ $attributes->class(['be-loading']) }} wire:loading.flex>
    <span class="be-loading__spinner" aria-hidden="true"></span>
    <span>{{ $message }}</span>
</div>
