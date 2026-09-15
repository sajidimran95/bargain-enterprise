{{--
    Generic module placeholder for screens not yet fully implemented.
    Toolbar actions still work: New (optional), Print, and a toast for Find/Excel.
--}}
@props([
    'title',
    'windowTitle' => null,
    'description' => 'This module will be implemented in a later phase.',
    'phase' => null,
    'newUrl' => null,
    'newLabel' => 'New',
])

@php
    $windowTitle = $windowTitle ?? $title;
@endphp

<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="windowTitle">{{ $windowTitle }}</x-slot>

    <div
        class="be-page"
        x-data
        @be-placeholder-find.window="$dispatch('be-toast', { message: 'Open a live list screen to use Find.' })"
        @be-placeholder-excel.window="$dispatch('be-toast', { message: 'Excel export is available on live list screens.' })"
    >
        <div class="be-toolbar">
            @if ($newUrl)
                <a href="{{ $newUrl }}" class="be-btn be-btn--primary">{{ $newLabel }}</a>
            @else
                <button
                    type="button"
                    class="be-btn be-btn--primary"
                    @click="$dispatch('be-toast', { message: 'Create form for {{ $title }} is coming next — use New on related list pages.' })"
                >New</button>
            @endif
            <button type="button" class="be-btn" @click="$dispatch('be-placeholder-find')">Find</button>
            <button type="button" class="be-btn" onclick="window.print()">Print</button>
            <button type="button" class="be-btn" @click="$dispatch('be-placeholder-excel')">Excel</button>
            <span class="ml-auto text-[11px] text-gray-500">Phase {{ $phase ?? '—' }} · list screens are live</span>
        </div>

        <div class="be-panel">
            <div class="be-panel__header">
                <h1 class="be-panel__title">{{ $title }}</h1>
                @if ($phase)
                    <span class="be-badge be-badge--warn">Detail form Phase {{ $phase }}</span>
                @endif
            </div>
            <div class="be-panel__body">
                <div class="be-empty">
                    <p class="mb-2 font-semibold text-gray-700">{{ $title }}</p>
                    <p>{{ $description }}</p>
                    <p class="mt-2 text-[12px] text-gray-500">
                        Use the sidebar submenus to open live list screens (New / Find / Print / Excel work there).
                    </p>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
