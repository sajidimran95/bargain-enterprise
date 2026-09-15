@props([
    'title',
    'newUrl' => null,
    'newLabel' => 'New',
])

<div class="be-list-header">
    <h1 class="be-list-header__title">{{ $title }}</h1>
    <div class="be-list-header__actions">
        @if ($newUrl)
            <a href="{{ $newUrl }}" class="be-btn be-btn--primary">{{ $newLabel }}</a>
        @endif
        {{ $actions ?? '' }}
        {{ $slot }}
    </div>
</div>
