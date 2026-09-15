@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->class(['be-page-header']) }}>
    <div>
        <h1 class="be-page-header__title">{{ $title }}</h1>
        @if ($subtitle)
            <p class="be-page-header__subtitle">{{ $subtitle }}</p>
        @endif
    </div>
    @isset($actions)
        <div class="be-page-header__actions">
            {{ $actions }}
        </div>
    @endisset
</div>
