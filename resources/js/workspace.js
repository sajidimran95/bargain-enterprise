/**
 * Desktop workspace helpers (parent shell + embed bridge).
 * Business logic stays in Livewire / PHP.
 */

document.addEventListener('alpine:init', () => {
    Alpine.data('workspaceChrome', (boot = {}) => ({
        contextMenu: { show: false, x: 0, y: 0, id: null, closable: true },
        confirm: { show: false, id: null, title: '' },

        init() {
            window.addEventListener('message', (event) => this.onFrameMessage(event));
            window.addEventListener('be-workspace-confirm-close', (event) => {
                const detail = event.detail || {};
                this.confirm = {
                    show: true,
                    id: detail.id,
                    title: detail.title || 'This tab',
                };
            });
        },

        syncFromLivewire() {
            // Livewire re-renders tabs; context menu closes.
            this.contextMenu.show = false;
        },

        openContextMenu(event, id) {
            const tab = (this.$wire.tabs || []).find((t) => t.id === id);
            this.contextMenu = {
                show: true,
                x: event.clientX,
                y: event.clientY,
                id,
                closable: tab ? !!tab.closable : true,
            };
        },

        runContext(action) {
            const id = this.contextMenu.id;
            this.contextMenu.show = false;
            if (!id) {
                return;
            }
            if (action === 'refresh') {
                this.$wire.refreshTab(id);
            } else if (action === 'close') {
                this.$wire.closeTab(id);
            } else if (action === 'close-others') {
                this.$wire.closeOtherTabs(id);
            } else if (action === 'close-all') {
                this.$wire.closeAllTabs();
            }
        },

        discardAndClose() {
            const id = this.confirm.id;
            this.confirm.show = false;
            if (id) {
                this.$wire.closeTab(id, true);
            }
        },

        onFrameMessage(event) {
            const data = event.data;
            if (!data || typeof data !== 'object') {
                return;
            }

            if (data.type === 'be-workspace-open' && data.route) {
                this.$wire.openRoute(data.route, data.params || {}, data.title || null, data.id || null);
            }
            if (data.type === 'be-workspace-dirty' && data.tabId) {
                this.$wire.setTabDirty(data.tabId, !!data.dirty);
            }
            if (data.type === 'be-workspace-title' && data.tabId && data.title) {
                this.$wire.setTabTitle(data.tabId, data.title);
            }
            if (data.type === 'be-workspace-close' && data.tabId) {
                this.$wire.closeTab(data.tabId);
            }
        },

        onKeydown(e) {
            if (!(e.ctrlKey || e.metaKey)) {
                return;
            }
            if (e.key.toLowerCase() === 'w') {
                e.preventDefault();
                const id = this.$wire.activeTabId;
                if (id) {
                    this.$wire.closeTab(id);
                }
            }
        },
    }));
});

window.beOpenWorkspace = function (routeName, params = {}, title = null) {
    // Inside a workspace iframe: always ask the parent shell — never navigate this frame to /dashboard.
    if (window.parent && window.parent !== window) {
        window.parent.postMessage({
            type: 'be-workspace-open',
            route: routeName,
            params: params || {},
            title: title,
        }, '*');
        return;
    }

    const shell = document.querySelector('[data-workspace-shell]');
    if (shell && window.Livewire) {
        const wireEl = shell.closest('[wire\\:id]') || shell;
        const id = wireEl.getAttribute('wire:id');
        if (id) {
            Livewire.find(id).call('openRoute', routeName, params, title);
            return;
        }
        Livewire.dispatch('workspace-open', { route: routeName, params, title });
        return;
    }

    const url = new URL(window.location.origin + '/dashboard');
    url.searchParams.set('open', routeName);
    Object.entries(params || {}).forEach(([key, value]) => {
        if (value !== null && value !== undefined && value !== '') {
            url.searchParams.set(key, value);
        }
    });
    window.location.href = url.toString();
};
