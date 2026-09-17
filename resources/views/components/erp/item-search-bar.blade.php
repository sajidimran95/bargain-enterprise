@props([
    'showAddLine' => false,
])

<div class="be-item-search" wire:click.outside="clearItemSearch">
    <div class="be-scan-bar be-scan-bar--compact">
        <label class="be-field__label be-field__label--caps mb-0">Item / Scan</label>
        <input
            x-ref="scanInput"
            type="text"
            class="be-input be-scan-input"
            wire:model.live.debounce.150ms="scanCode"
            wire:keydown.enter.prevent="scanItem"
            wire:keydown.escape.prevent="clearItemSearch"
            placeholder="Search code / name — top 5 matches"
            autocomplete="off"
            spellcheck="false"
        >
        <button type="button" class="be-btn be-btn--primary" wire:click="scanItem">Add</button>
        @if ($showAddLine)
            <button type="button" class="be-btn" wire:click="addLine">Add Line</button>
        @endif
    </div>

    @if (count($this->itemSearchResults) > 0)
        <ul class="be-item-search__results" role="listbox">
            @foreach ($this->itemSearchResults as $result)
                <li wire:key="item-search-{{ $result['id'] }}">
                    <button
                        type="button"
                        class="be-item-search__result"
                        wire:click="selectItemSearchResult({{ $result['id'] }})"
                    >
                        <span class="be-item-search__code">{{ $result['code'] }}</span>
                        <span class="be-item-search__label">{{ $result['label'] }}</span>
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>
