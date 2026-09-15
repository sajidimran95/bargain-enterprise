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

    @if ($tab !== 'standard')
        <div class="be-report-center__empty">
            <p>{{ $tabs[$tab] }} reports will appear here. Use Standard for the full catalog.</p>
        </div>
    @else
        <div class="be-report-center__body">
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

            <div class="be-report-center__content">
                <h2 class="be-report-center__category-title">{{ $activeCategory['short'] ?? $activeCategory['label'] }}</h2>

                @forelse ($groups as $group)
                    @if (! empty($group['title']))
                        <h3 class="be-report-center__group-title">{{ $group['title'] }}</h3>
                    @endif

                    <div class="be-report-center__grid">
                        @foreach ($group['reports'] as $report)
                            <article class="be-report-card">
                                <h4 class="be-report-card__title">{{ $report['label'] }}</h4>
                                <div class="be-report-card__preview" aria-hidden="true">
                                    <span class="be-report-card__sample">SAMPLE</span>
                                    <div class="be-report-card__preview-lines">
                                        <span></span><span></span><span></span><span></span>
                                    </div>
                                </div>
                                <div class="be-report-card__dates">
                                    <label>
                                        <span>Dates:</span>
                                        <select class="be-input be-input--sm" disabled>
                                            <option>This Fiscal Year-to-date</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="be-report-card__actions">
                                    @if (! empty($report['route']))
                                        <x-erp.workspace-link
                                            :route="$report['route']"
                                            class="be-report-card__run"
                                            title="Run Report"
                                        >▶</x-erp.workspace-link>
                                    @else
                                        <button
                                            type="button"
                                            class="be-report-card__run"
                                            title="Coming soon"
                                            @click="window.dispatchEvent(new CustomEvent('be-toast', { detail: { message: @js(($report['label'] ?? 'Report').' — coming soon.') } }))"
                                        >▶</button>
                                    @endif
                                    <span class="be-report-card__icon" title="Preview">🔍</span>
                                    <span class="be-report-card__icon be-report-card__icon--fav" title="Favorite">♥</span>
                                    <span class="be-report-card__icon be-report-card__icon--help" title="Help">?</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @empty
                    <p class="be-report-center__empty-text">No reports match your search.</p>
                @endforelse
            </div>
        </div>
    @endif
</div>
