@props([
    'tabs' => [],
    'active' => null,
])

<div {{ $attributes->class(['be-tabs']) }}>
    <div class="be-tabs__list" role="tablist">
        @foreach ($tabs as $key => $label)
            <button
                type="button"
                role="tab"
                class="be-tabs__tab {{ $active === $key ? 'is-active' : '' }}"
                wire:click="$set('activeTab', '{{ $key }}')"
                aria-selected="{{ $active === $key ? 'true' : 'false' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>
    <div class="be-tabs__panel" role="tabpanel">
        {{ $slot }}
    </div>
</div>
