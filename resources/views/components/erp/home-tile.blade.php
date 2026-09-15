@props([
    'route' => null,
    'title' => null,
    'icon' => 'file',
    'tone' => 'default',
    'badge' => null,
    'toast' => null,
])

@php
    $iconView = 'components.erp.icons.'.$icon;
@endphp

@if ($route)
    <x-erp.workspace-link :route="$route" :title="$title" class="be-home-tile">
        <span class="be-home-tile__icon be-home-tile__icon--{{ $tone }}" aria-hidden="true">
            @include($iconView)
        </span>
        <span class="be-home-tile__label">{{ $slot }}</span>
        @if ($badge)
            <span class="be-home-tile__badge">{{ $badge }}</span>
        @endif
    </x-erp.workspace-link>
@else
    <button
        type="button"
        class="be-home-tile"
        title="{{ $title }}"
        @if ($toast)
            @click="window.dispatchEvent(new CustomEvent('be-toast', { detail: { message: @js($toast) } }))"
        @endif
    >
        <span class="be-home-tile__icon be-home-tile__icon--{{ $tone }}" aria-hidden="true">
            @include($iconView)
        </span>
        <span class="be-home-tile__label">{{ $slot }}</span>
        @if ($badge)
            <span class="be-home-tile__badge">{{ $badge }}</span>
        @endif
    </button>
@endif
