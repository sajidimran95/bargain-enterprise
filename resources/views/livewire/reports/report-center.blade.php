<div class="be-page be-report-center">
    <div class="be-report-center__header">
        <h1 class="be-report-center__title">Report Center</h1>
        <div class="be-report-center__search">
            <input
                type="search"
                class="be-input"
                placeholder="Search reports"
                wire:model.live.debounce.250ms="search"
                aria-label="Search reports"
            >
        </div>
    </div>

    <div class="be-report-center__tabs" role="tablist">
        @foreach ($tabs as $key => $label)
            <button
                type="button"
                role="tab"
                class="be-report-center__tab {{ $tab === $key ? 'is-active' : '' }}"
                wire:click="selectTab(@js($key))"
                aria-selected="{{ $tab === $key ? 'true' : 'false' }}"
            >{{ $label }}</button>
        @endforeach
    </div>

    <div class="be-report-center__body">
        @if ($tab === 'standard')
            <aside class="be-report-center__nav" aria-label="Report categories">
                @foreach ($categories as $cat)
                    <button
                        type="button"
                        class="be-report-center__nav-item {{ $category === $cat['id'] ? 'is-active' : '' }}"
                        wire:click="selectCategory(@js($cat['id']))"
                    >
                        <span>{{ $cat['short'] }}</span>
                        @if ($category === $cat['id'])
                            <span class="be-report-center__nav-arrow" aria-hidden="true">▸</span>
                        @endif
                    </button>
                @endforeach
            </aside>
        @endif

        <div class="be-report-center__content">
            <h2 class="be-report-center__category-title">
                @if ($tab === 'standard')
                    {{ $activeCategory['short'] ?? $activeCategory['label'] }}
                @else
                    {{ $tabs[$tab] }}
                @endif
            </h2>

            @forelse ($groups as $group)
                @if (! empty($group['title']))
                    <h3 class="be-report-center__group-title">{{ $group['title'] }}</h3>
                @endif

                <div class="be-report-center__grid">
                    @foreach ($group['reports'] as $report)
                        <article class="be-report-card" wire:key="report-{{ $report['key'] }}">
                            <h4 class="be-report-card__title">{{ $report['label'] }}</h4>
                            <div class="be-report-card__preview">
                                <table class="be-report-card__mini">
                                    <thead>
                                        <tr>
                                            @foreach ($report['preview']['columns'] as $column)
                                                <th>{{ $column }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($report['preview']['rows'] as $row)
                                            <tr>
                                                @foreach ($row as $cell)
                                                    <td>{{ $cell }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="be-report-card__dates">
                                <label>
                                    <span>Dates:</span>
                                    <select
                                        class="be-input be-input--sm be-report-card__dates-select"
                                        size="1"
                                        wire:change="setDatePreset({{ \Illuminate\Support\Js::from($report['key']) }}, $event.target.value)"
                                    >
                                        @foreach ($datePresetOptions as $value => $label)
                                            <option value="{{ $value }}" @selected($report['date_preset'] === $value)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                                <div class="be-report-card__date-range">
                                    <input
                                        type="date"
                                        class="be-input be-input--sm"
                                        value="{{ $report['from'] }}"
                                        title="{{ $report['from_display'] }}"
                                        wire:change="setCustomFrom({{ \Illuminate\Support\Js::from($report['key']) }}, $event.target.value)"
                                        aria-label="From date"
                                    >
                                    <input
                                        type="date"
                                        class="be-input be-input--sm"
                                        value="{{ $report['to'] }}"
                                        title="{{ $report['to_display'] }}"
                                        wire:change="setCustomTo({{ \Illuminate\Support\Js::from($report['key']) }}, $event.target.value)"
                                        aria-label="To date"
                                    >
                                </div>
                            </div>
                            <div class="be-report-card__actions">
                                @if (! empty($report['route']))
                                    <x-erp.workspace-link
                                        :route="$report['route']"
                                        class="be-report-card__run"
                                        title="Run Report"
                                        wire:click="recordRecent({{ \Illuminate\Support\Js::from($report['key']) }})"
                                    >▶</x-erp.workspace-link>
                                @else
                                    <button
                                        type="button"
                                        class="be-report-card__run"
                                        title="Unavailable"
                                        disabled
                                    >▶</button>
                                @endif
                                @if (! empty($report['route']))
                                    <x-erp.workspace-link
                                        :route="$report['route']"
                                        class="be-report-card__icon"
                                        title="Display Report"
                                        wire:click="recordRecent({{ \Illuminate\Support\Js::from($report['key']) }})"
                                    >🔍</x-erp.workspace-link>
                                @else
                                    <span class="be-report-card__icon" title="Preview">🔍</span>
                                @endif
                                <button
                                    type="button"
                                    class="be-report-card__icon be-report-card__icon--fav {{ $report['is_favorite'] ? 'is-on' : '' }}"
                                    title="{{ $report['is_favorite'] ? 'Remove Favorite' : 'Add to Favorites' }}"
                                    wire:click="toggleFavorite({{ \Illuminate\Support\Js::from($report['key']) }})"
                                >♥</button>
                                <button
                                    type="button"
                                    class="be-report-card__icon be-report-card__icon--help {{ $report['is_memorized'] ? 'is-on' : '' }}"
                                    title="{{ $report['is_memorized'] ? 'Forget Memorized Report' : 'Memorize Report' }}"
                                    wire:click="toggleMemorize({{ \Illuminate\Support\Js::from($report['key']) }})"
                                >?</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            @empty
                <p class="be-report-center__empty-text">
                    @if ($tab === 'favorites')
                        No favorite reports yet. Click the heart on any Standard report card.
                    @elseif ($tab === 'recent')
                        No recent reports yet. Run a report from Standard to populate this list.
                    @elseif ($tab === 'memorized')
                        No memorized reports yet. Click ? on a report card to memorize it.
                    @else
                        No reports match your search.
                    @endif
                </p>
            @endforelse
        </div>
    </div>
</div>
