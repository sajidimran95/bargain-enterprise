<div
    class="be-global-search"
    x-data
    @be-focus-search.window="$refs.searchInput.focus()"
    @click.outside="$wire.close()"
    @keydown.escape.window="$wire.close()"
>
    <label for="global-search" class="sr-only">Search company</label>
    <div class="be-global-search__field">
        <svg class="be-global-search__icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M8.5 3a5.5 5.5 0 104.06 9.06l3.19 3.19a.75.75 0 101.06-1.06l-3.19-3.19A5.5 5.5 0 008.5 3zm-4 5.5a4 4 0 118 0 4 4 0 01-8 0z" clip-rule="evenodd"/>
        </svg>
        <input
            id="global-search"
            x-ref="searchInput"
            type="search"
            class="be-input be-global-search__input"
            placeholder="Search customers, invoices, items, vendors…"
            wire:model.live.debounce.250ms="query"
            autocomplete="off"
            aria-autocomplete="list"
            aria-expanded="{{ $open ? 'true' : 'false' }}"
            aria-controls="global-search-results"
        >
    </div>

    @if ($open)
        <div id="global-search-results" class="be-global-search__panel" role="listbox">
            @forelse ($groups as $group)
                <div class="be-global-search__group">
                    <div class="be-global-search__group-label">{{ $group['label'] }}</div>
                    @foreach ($group['results'] as $result)
                        <button
                            type="button"
                            class="be-global-search__result"
                            role="option"
                            wire:click="openResult(
                                {{ \Illuminate\Support\Js::from($result['route']) }},
                                {{ \Illuminate\Support\Js::from($result['params'] ?? []) }},
                                {{ \Illuminate\Support\Js::from($result['title_tab'] ?? $result['title']) }}
                            )"
                        >
                            <div class="be-global-search__result-title">{{ $result['title'] }}</div>
                            @if (! empty($result['subtitle']))
                                <div class="be-global-search__result-sub">{{ $result['subtitle'] }}</div>
                            @endif
                        </button>
                    @endforeach
                </div>
            @empty
                <div class="be-global-search__empty">No matches for “{{ $query }}”</div>
            @endforelse
        </div>
    @endif
</div>
