@props([
    'label' => 'Actions',
])

<div
    x-data="{ open: false }"
    class="be-dropdown"
    @click.outside="open = false"
    {{ $attributes }}
>
    <button type="button" class="be-btn be-dropdown__trigger" @click="open = !open">
        {{ $label }}
        <span aria-hidden="true">▾</span>
    </button>
    <div x-show="open" x-cloak class="be-dropdown__menu" role="menu">
        {{ $slot }}
    </div>
</div>
