@props([
    'route',
    'title' => null,
    'icon' => 'file',
    'tone' => 'default',
    'badge' => null,
])

@php
    $iconView = 'components.erp.icons.'.$icon;
@endphp

<x-erp.workspace-link :route="$route" :title="$title" class="be-home-tile">
    <span class="be-home-tile__icon be-home-tile__icon--{{ $tone }}" aria-hidden="true">
        @include($iconView)
    </span>
    <span class="be-home-tile__label">{{ $slot }}</span>
    @if ($badge)
        <span class="be-home-tile__badge">{{ $badge }}</span>
    @endif
</x-erp.workspace-link>
