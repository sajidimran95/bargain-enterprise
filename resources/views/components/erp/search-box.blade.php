@props([
    'placeholder' => 'Search…',
])

@php
    $inputAttributes = $attributes->except('class');
@endphp

<div {{ $attributes->only('class')->class(['be-search-box']) }}>
    <svg class="be-search-box__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 013.95 9.3l3.12 3.13a.75.75 0 11-1.06 1.06l-3.13-3.12A5.5 5.5 0 118.5 3zm0 1.5a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd"/>
    </svg>
    <input
        type="search"
        class="be-input be-search-box__input"
        placeholder="{{ $placeholder }}"
        {{ $inputAttributes }}
    >
</div>
