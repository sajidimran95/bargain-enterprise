<div class="be-sidebar__body">
    <nav class="be-sidebar__nav" x-ref="shortcutNav" aria-label="Navigation">
        @forelse ($shortcuts as $item)
            <div
                class="be-sidebar__link-wrap"
                wire:key="shortcut-{{ $item['key'] }}"
                data-label="{{ strtolower($item['label']) }}"
            >
                <a
                    href="{{ route('dashboard', ['open' => $item['route']]) }}"
                    class="be-sidebar__link {{ $item['active'] ? 'is-active' : '' }}"
                    @click.prevent="beOpenWorkspace(@js($item['route']))"
                >
                    <span class="be-sidebar__icon" aria-hidden="true">@include('components.erp.icons.'.$item['icon'])</span>
                    <span class="flex-1">{{ $item['label'] }}</span>
                </a>
            </div>
        @empty
            <p class="be-sidebar__muted px-2">No navigation items. Use the gear under My Shortcuts to add some.</p>
        @endforelse
    </nav>

    <div class="be-sidebar__myshortcuts" x-data="{ expanded: true }">
        <div class="be-sidebar__myshortcuts-head">
            <button
                type="button"
                class="be-sidebar__myshortcuts-title"
                @click="expanded = !expanded"
            >
                <span class="be-sidebar__myshortcuts-chevron" :class="expanded ? 'is-open' : ''" aria-hidden="true">▾</span>
                My Shortcuts
            </button>
            <button
                type="button"
                class="be-sidebar__gear"
                title="Customize shortcuts"
                wire:click="openAdd"
                aria-label="Customize My Shortcuts"
            >
                <svg viewBox="0 0 16 16" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <circle cx="8" cy="8" r="2.2"/>
                    <path d="M8 1.5v1.4M8 13.1v1.4M1.5 8h1.4M13.1 8h1.4M3.1 3.1l1 1M11.9 11.9l1 1M3.1 12.9l1-1M11.9 4.1l1-1"/>
                </svg>
            </button>
        </div>

        <div class="be-sidebar__myshortcuts-body" x-show="expanded" x-cloak>
            <a
                href="{{ route('dashboard', ['open' => 'accounting.chart']) }}"
                class="be-sidebar__footer-link"
                @click.prevent="beOpenWorkspace('accounting.chart')"
            >View Balances</a>
            <a
                href="{{ route('dashboard', ['open' => 'reports.index']) }}"
                class="be-sidebar__footer-link"
                @click.prevent="beOpenWorkspace('reports.index')"
            >Run Favorite Reports</a>
            <div class="be-sidebar__footer-link be-sidebar__footer-link--static">Open Windows</div>

            <div class="be-sidebar__open-windows">
                @forelse ($openWindows as $wsTab)
                    <button
                        type="button"
                        class="be-sidebar__window-item {{ ($activeWindowId ?? '') === ($wsTab['id'] ?? '') ? 'is-active' : '' }}"
                        @click="beOpenWorkspace(@js($wsTab['route'] ?? 'dashboard.home'), @js($wsTab['params'] ?? []))"
                    >{{ $wsTab['title'] ?? 'Window' }}</button>
                @empty
                    <p class="be-sidebar__muted">{{ $currentWindowTitle }}</p>
                @endforelse
            </div>
        </div>
    </div>

    @if ($showAdd)
        <div class="be-modal be-modal--sidebar">
            <div class="be-modal__backdrop" wire:click="closeAdd"></div>
            <div class="be-modal__panel be-modal__panel--sm" wire:click.stop>
                <div class="be-modal__header">
                    <h3 class="be-modal__title">My Shortcuts</h3>
                    <button type="button" class="be-modal__close" wire:click="closeAdd">×</button>
                </div>
                <div class="be-modal__body space-y-3">
                    <div>
                        <p class="mb-1 text-[11px] font-semibold text-gray-600">Pinned (click × to remove)</p>
                        <div class="be-sidebar__add-list border border-gray-200 p-1">
                            @forelse ($shortcuts as $item)
                                <div class="be-sidebar__add-item be-sidebar__add-item--pinned" wire:key="pinned-{{ $item['key'] }}">
                                    <span class="be-sidebar__icon" aria-hidden="true">@include('components.erp.icons.'.$item['icon'])</span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    <button type="button" class="be-link-btn" wire:click="removeShortcut(@js($item['key']))">×</button>
                                </div>
                            @empty
                                <p class="be-sidebar__muted">None pinned yet.</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <p class="mb-1 text-[11px] font-semibold text-gray-600">Add shortcut</p>
                        <div class="be-sidebar__add-list border border-gray-200 p-1">
                            @forelse ($available as $item)
                                <button
                                    type="button"
                                    class="be-sidebar__add-item"
                                    wire:key="add-{{ $item['key'] }}"
                                    wire:click="addShortcut(@js($item['key']))"
                                >
                                    <span class="be-sidebar__icon" aria-hidden="true">@include('components.erp.icons.'.$item['icon'])</span>
                                    <span>{{ $item['label'] }}</span>
                                </button>
                            @empty
                                <p class="be-sidebar__muted px-2 py-1">All available shortcuts are already pinned.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="be-modal__footer">
                    <button type="button" class="be-btn" wire:click="resetShortcuts" wire:confirm="Reset sidebar shortcuts to defaults?">Reset Defaults</button>
                    <button type="button" class="be-btn be-btn--primary" wire:click="closeAdd">Done</button>
                </div>
            </div>
        </div>
    @endif
</div>
