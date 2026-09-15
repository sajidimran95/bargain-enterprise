@props([
    'type' => 'info',
])

@php
    $class = match ($type) {
        'success' => 'be-alert be-alert--success',
        'warning' => 'be-alert be-alert--warning',
        'danger' => 'be-alert be-alert--danger',
        default => 'be-alert',
    };
@endphp

<div {{ $attributes->class([$class]) }} role="status">
    {{ $slot }}
</div>
