@props([
    'searchModel' => 'search',
    'placeholder' => 'Type a word or phrase…',
    'withinLabel' => null,
    'withinModel' => null,
    'withinOptions' => [],
])

<div {{ $attributes->class('be-look-for') }}>
    <div class="be-field">
        <label class="be-field__label">Look for</label>
        <x-erp.input
            x-ref="listSearch"
            wire:model.live.debounce.300ms="{{ $searchModel }}"
            :placeholder="$placeholder"
            class="w-56"
        />
    </div>

    @if ($withinModel && $withinOptions !== [])
        <div class="be-field">
            <label class="be-field__label">{{ $withinLabel ?? 'Search within' }}</label>
            <x-erp.select wire:model.live="{{ $withinModel }}" :options="$withinOptions" />
        </div>
    @endif

    {{ $slot }}
</div>
