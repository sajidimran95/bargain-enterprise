<div
    class="be-workspace"
    data-workspace-shell
    x-data="workspaceChrome(@js([
        'activeTabId' => $activeTabId,
        'tabs' => $tabs,
    ]))"
    x-on:workspace-tabs-updated.window="syncFromLivewire()"
    @keydown.window="onKeydown($event)"
>
    {{-- Hard stop: never paint the shell inside a tab iframe (nested chrome). --}}
    <script>
        if (window.parent && window.parent !== window) {
            window.location.replace(@js(route('dashboard.home', ['embed' => 1, 'tab_id' => 'dashboard'])));
        }
    </script>
    <div class="be-workspace__tabs" role="tablist" aria-label="Open windows" @click.right.prevent="">
        <div class="be-workspace__tabs-scroll">
            @foreach ($tabs as $tab)
                <div
                    wire:key="ws-tab-{{ $tab['id'] }}"
                    class="be-workspace__tab {{ $tab['id'] === $activeTabId ? 'is-active' : '' }} {{ !empty($tab['dirty']) ? 'is-dirty' : '' }}"
                    role="tab"
                    aria-selected="{{ $tab['id'] === $activeTabId ? 'true' : 'false' }}"
                    @click="$wire.activateTab(@js($tab['id']))"
                    @contextmenu.prevent="openContextMenu($event, @js($tab['id']))"
                    title="{{ $tab['title'] }}"
                >
                    <span class="be-workspace__tab-title">{{ $tab['title'] }}</span>
                    @if ($tab['closable'] ?? true)
                        <button
                            type="button"
                            class="be-workspace__tab-close"
                            title="Close"
                            @click.stop="$wire.closeTab(@js($tab['id']))"
                        >×</button>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="be-workspace__tab-actions">
            <button type="button" class="be-workspace__tab-action" title="Close all closable tabs" wire:click="closeAllTabs">Close All</button>
        </div>
    </div>

    <div class="be-workspace__panels">
        @foreach ($tabs as $tab)
            <div
                wire:key="ws-panel-{{ $tab['id'] }}-{{ $tab['refresh_token'] ?? 0 }}"
                class="be-workspace__panel {{ $tab['id'] === $activeTabId ? 'is-active' : '' }}"
                data-tab-id="{{ $tab['id'] }}"
                @if ($tab['id'] !== $activeTabId) hidden @endif
            >
                <iframe
                    wire:ignore
                    class="be-workspace__frame"
                    title="{{ $tab['title'] }}"
                    src="{{ \App\Support\Workspace\WorkspaceCatalog::embedUrl($tab['route'] ?? 'dashboard.home', $tab['params'] ?? [], $tab['id']) }}&rt={{ $tab['refresh_token'] ?? 0 }}"
                    data-workspace-tab="{{ $tab['id'] }}"
                    loading="{{ $tab['id'] === $activeTabId ? 'eager' : 'lazy' }}"
                ></iframe>
            </div>
        @endforeach
    </div>

    <div
        x-show="contextMenu.show"
        x-cloak
        class="be-workspace__menu"
        :style="`top:${contextMenu.y}px;left:${contextMenu.x}px`"
        @click.outside="contextMenu.show = false"
    >
        <button type="button" @click="runContext('refresh')">Refresh</button>
        <button type="button" @click="runContext('close')" x-show="contextMenu.closable">Close</button>
        <button type="button" @click="runContext('close-others')">Close Others</button>
        <button type="button" @click="runContext('close-all')">Close All</button>
    </div>

    <div
        x-show="confirm.show"
        x-cloak
        class="be-workspace__confirm-backdrop"
        @keydown.escape.window="confirm.show = false"
    >
        <div class="be-workspace__confirm" role="dialog" aria-modal="true">
            <p class="be-workspace__confirm-title">Unsaved changes will be lost. Continue?</p>
            <p class="be-workspace__confirm-body" x-text="confirm.title"></p>
            <div class="be-workspace__confirm-actions">
                <button type="button" class="be-btn" @click="confirm.show = false">Cancel</button>
                <button type="button" class="be-btn be-btn--primary" @click="discardAndClose()">Discard Changes</button>
            </div>
        </div>
    </div>
</div>

@script
<script>
    $wire.on('workspace-confirm-close', (payload) => {
        const detail = Array.isArray(payload) ? payload[0] : payload;
        window.dispatchEvent(new CustomEvent('be-workspace-confirm-close', { detail }));
    });
</script>
@endscript
