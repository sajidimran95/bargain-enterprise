<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $isWorkspaceEmbed = request()->boolean('embed')
        || request()->header('X-Workspace-Embed') === '1'
        || request()->header('Sec-Fetch-Dest') === 'iframe';
@endphp
@if ($isWorkspaceEmbed)
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @isset($title)
            {{ $title }} — {{ config('bargain.company_name') }}
        @else
            {{ config('bargain.company_name') }}
        @endisset
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="be-body be-body--embed" data-workspace-embed="1">
    {{ $slot }}
    @livewireScripts
    <script>
        (function () {
            const params = new URLSearchParams(window.location.search);
            const tabId = params.get('tab_id');
            window.beWorkspace = {
                tabId,
                open(route, routeParams = {}, title = null, id = null) {
                    window.parent.postMessage({ type: 'be-workspace-open', route, params: routeParams, title, id }, '*');
                },
                setDirty(dirty) {
                    if (!tabId) return;
                    window.parent.postMessage({ type: 'be-workspace-dirty', tabId, dirty: !!dirty }, '*');
                },
                setTitle(title) {
                    if (!tabId) return;
                    window.parent.postMessage({ type: 'be-workspace-title', tabId, title }, '*');
                },
            };
            document.addEventListener('input', () => window.beWorkspace.setDirty(true), { once: true, capture: true });
        })();
    </script>
</body>
</html>
@else
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @isset($title)
            {{ $title }} — {{ config('bargain.company_name') }}
        @else
            {{ config('bargain.company_name') }} — Bargain Enterprise
        @endisset
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body
    class="be-body"
    x-data="erpShell()"
    @keydown.window="handleShortcuts($event)"
>
    <div class="be-app" @be-toast.window="showToast(($event.detail && $event.detail.message) || 'Saved')">
        {{-- Title / menu / search strip --}}
        <header class="be-top">
            <div class="be-titlebar">
                <span class="be-titlebar__company">{{ config('bargain.company_name') }}</span>
                <span class="be-titlebar__sep">—</span>
                <span class="be-titlebar__product">Bargain Enterprise POS/ERP</span>
                @isset($windowTitle)
                    <span class="be-titlebar__sep">—</span>
                    <span class="be-titlebar__window">[{{ $windowTitle }}]</span>
                @endisset
            </div>

            <div class="be-menubar" role="menubar" aria-label="Main menu" x-data="{ open: null }" @keydown.escape.window="open = null">
                @php
                    $menuBar = config('erp_menubar');
                @endphp
                @foreach ($menuBar as $menu => $items)
                    <div class="be-menubar__group" @mouseenter="open = @js($menu)" @mouseleave="open = null">
                        <button
                            type="button"
                            class="be-menubar__item"
                            role="menuitem"
                            :aria-expanded="(open === @js($menu)).toString()"
                            @click="open = open === @js($menu) ? null : @js($menu)"
                        >{{ $menu }}</button>
                        <div class="be-menubar__dropdown" x-show="open === @js($menu)" x-cloak role="menu">
                            @foreach ($items as $entry)
                                @if (!empty($entry['separator']))
                                    <div class="be-menubar__sep" role="separator"></div>
                                @elseif (!empty($entry['action']))
                                    <button
                                        type="button"
                                        class="be-menubar__link"
                                        role="menuitem"
                                        @click="handleMenuAction(@js($entry)); open = null"
                                    >{{ $entry['label'] }}</button>
                                @else
                                    <a
                                        href="{{ route('dashboard', ['open' => $entry['route']]) }}"
                                        class="be-menubar__link"
                                        role="menuitem"
                                        @click.prevent="beOpenWorkspace(@js($entry['route'])); open = null"
                                    >{{ $entry['label'] }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="be-utilitybar">
                <div class="be-utilitybar__search">
                    <livewire:search.global-search />
                </div>
                <div class="be-utilitybar__user">
                    <span class="be-utilitybar__user-name">{{ auth()->user()->name }}</span>
                    <span class="be-utilitybar__user-role">{{ auth()->user()->primaryRoleLabel() }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="be-link-btn">Sign out</button>
                    </form>
                </div>
            </div>
        </header>

        <div class="be-shell">
            <aside class="be-sidebar" aria-label="My Shortcuts">
                <div class="be-sidebar__search">
                    <input
                        type="search"
                        class="be-input be-input--sidebar"
                        placeholder="Search shortcuts"
                        x-ref="shortcutSearch"
                        @input="filterShortcuts($event.target.value)"
                        aria-label="Search shortcuts"
                    >
                </div>

                <nav class="be-sidebar__nav" x-ref="shortcutNav">
                    <p class="be-sidebar__heading">My Shortcuts</p>
                    @php
                        $navGroups = [
                            [
                                'label' => 'Home',
                                'route' => 'dashboard.home',
                                'icon' => 'home',
                                'match' => 'dashboard.home',
                            ],
                            [
                                'label' => 'Snapshots',
                                'route' => 'dashboard.snapshots',
                                'icon' => 'chart',
                                'match' => 'dashboard.snapshots',
                            ],
                            [
                                'label' => 'Customers',
                                'icon' => 'users',
                                'match' => 'customers.*',
                                'children' => [
                                    ['label' => 'Customer Center', 'route' => 'customers.index'],
                                    ['label' => 'New Customer', 'route' => 'customers.create'],
                                ],
                            ],
                            [
                                'label' => 'Vendors',
                                'icon' => 'truck',
                                'match' => 'vendors.*',
                                'children' => [
                                    ['label' => 'Vendor Center', 'route' => 'vendors.index'],
                                    ['label' => 'New Vendor', 'route' => 'vendors.create'],
                                ],
                            ],
                            [
                                'label' => 'Items',
                                'icon' => 'box',
                                'match' => ['items.*', 'lookups.*'],
                                'children' => [
                                    ['label' => 'Item List', 'route' => 'items.index'],
                                    ['label' => 'New Item', 'route' => 'items.create'],
                                    ['label' => 'Lookups', 'route' => 'lookups.index'],
                                ],
                            ],
                            [
                                'label' => 'Inventory',
                                'icon' => 'layers',
                                'match' => 'inventory.*',
                                'children' => [
                                    ['label' => 'Stock Status', 'route' => 'inventory.index'],
                                    ['label' => 'Adjustments', 'route' => 'inventory.adjustments'],
                                ],
                            ],
                            [
                                'label' => 'Sales',
                                'icon' => 'file',
                                'match' => ['invoices.*', 'payments.*', 'quotes.*', 'sales-orders.*', 'credit-memos.*', 'sales-receipts.*'],
                                'children' => [
                                    ['label' => 'Invoice List', 'route' => 'invoices.index'],
                                    ['label' => 'Create Invoices', 'route' => 'invoices.create'],
                                    ['label' => 'Receive Payments', 'route' => 'payments.index'],
                                    ['label' => 'Quotes', 'route' => 'quotes.index'],
                                    ['label' => 'Sales Orders', 'route' => 'sales-orders.index'],
                                    ['label' => 'Credit Memos', 'route' => 'credit-memos.index'],
                                    ['label' => 'Sales Receipts', 'route' => 'sales-receipts.index'],
                                ],
                            ],
                            [
                                'label' => 'Purchasing',
                                'icon' => 'cart',
                                'match' => ['purchase-orders.*', 'goods-receipts.*', 'vendor-bills.*', 'vendor-payments.*'],
                                'children' => [
                                    ['label' => 'Purchase Orders', 'route' => 'purchase-orders.index'],
                                    ['label' => 'Receive Inventory', 'route' => 'goods-receipts.index'],
                                    ['label' => 'Enter Bills', 'route' => 'vendor-bills.index'],
                                    ['label' => 'Pay Bills', 'route' => 'vendor-payments.index'],
                                ],
                            ],
                            [
                                'label' => 'Banking',
                                'icon' => 'bank',
                                'match' => ['banking.*', 'deposits.*', 'checks.*', 'reconciliation.*'],
                                'children' => [
                                    ['label' => 'Bank Accounts', 'route' => 'banking.index'],
                                    ['label' => 'Deposits', 'route' => 'deposits.index'],
                                    ['label' => 'Checks', 'route' => 'checks.index'],
                                    ['label' => 'Reconcile', 'route' => 'reconciliation.index'],
                                ],
                            ],
                            [
                                'label' => 'Accounting',
                                'icon' => 'ledger',
                                'match' => 'accounting.*',
                                'children' => [
                                    ['label' => 'Chart of Accounts', 'route' => 'accounting.chart'],
                                    ['label' => 'Journal Entries', 'route' => 'accounting.journals'],
                                ],
                            ],
                            [
                                'label' => 'Reports',
                                'route' => 'reports.index',
                                'icon' => 'report',
                                'match' => 'reports.*',
                            ],
                            [
                                'label' => 'Audit Log',
                                'route' => 'audit.index',
                                'icon' => 'file',
                                'match' => 'audit.*',
                            ],
                            [
                                'label' => 'QB Import',
                                'route' => 'import.index',
                                'icon' => 'gear',
                                'match' => 'import.*',
                            ],
                            [
                                'label' => 'Settings',
                                'route' => 'settings.index',
                                'icon' => 'gear',
                                'match' => 'settings.*',
                            ],
                        ];

                        $routeMatches = function ($match) {
                            foreach ((array) $match as $pattern) {
                                if (request()->routeIs($pattern)) {
                                    return true;
                                }
                            }

                            return false;
                        };
                    @endphp
                    @foreach ($navGroups as $item)
                        @php
                            $isActive = $routeMatches($item['match'] ?? ($item['route'] ?? ''));
                            $hasChildren = ! empty($item['children']);
                        @endphp
                        @if ($hasChildren)
                            <div
                                class="be-sidebar__group"
                                data-label="{{ strtolower($item['label'].' '.collect($item['children'])->pluck('label')->implode(' ')) }}"
                                x-data="{ open: {{ $isActive ? 'true' : 'false' }} }"
                            >
                                <button
                                    type="button"
                                    class="be-sidebar__link be-sidebar__link--parent {{ $isActive ? 'is-active' : '' }}"
                                    @click="open = !open"
                                    :aria-expanded="open.toString()"
                                >
                                    <span class="be-sidebar__icon" aria-hidden="true">@include('components.erp.icons.'.$item['icon'])</span>
                                    <span class="flex-1 text-left">{{ $item['label'] }}</span>
                                    <span class="be-sidebar__chevron" :class="open ? 'is-open' : ''" aria-hidden="true">▸</span>
                                </button>
                                <div class="be-sidebar__sub" x-show="open" x-cloak>
                                    @foreach ($item['children'] as $child)
                                        <a
                                            href="{{ route('dashboard', ['open' => $child['route']]) }}"
                                            class="be-sidebar__link be-sidebar__link--child {{ request()->routeIs($child['route']) ? 'is-active' : '' }}"
                                            data-label="{{ strtolower($child['label']) }}"
                                            @click.prevent="beOpenWorkspace(@js($child['route']))"
                                        >
                                            <span>{{ $child['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a
                                href="{{ route('dashboard', ['open' => $item['route']]) }}"
                                class="be-sidebar__link {{ $isActive ? 'is-active' : '' }}"
                                data-label="{{ strtolower($item['label']) }}"
                                @click.prevent="beOpenWorkspace(@js($item['route']))"
                            >
                                <span class="be-sidebar__icon" aria-hidden="true">@include('components.erp.icons.'.$item['icon'])</span>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </nav>

                <div class="be-sidebar__footer">
                    <a href="{{ route('dashboard', ['open' => 'accounting.chart']) }}" class="be-sidebar__footer-link" @click.prevent="beOpenWorkspace('accounting.chart')">View Balances</a>
                    <a href="{{ route('dashboard', ['open' => 'reports.index']) }}" class="be-sidebar__footer-link" @click.prevent="beOpenWorkspace('reports.index')">Run Favorite Reports</a>
                    <div class="be-sidebar__open-windows">
                        <p class="be-sidebar__heading">Open Windows</p>
                        @forelse (($workspaceTabs ?? []) as $wsTab)
                            <button
                                type="button"
                                class="be-sidebar__window-item {{ ($workspaceActiveId ?? '') === $wsTab['id'] ? 'is-active' : '' }}"
                                @click="beOpenWorkspace(@js($wsTab['route']), @js($wsTab['params'] ?? []))"
                            >{{ $wsTab['title'] }}</button>
                        @empty
                            <p class="be-sidebar__muted">{{ $windowTitle ?? 'Home Page' }}</p>
                        @endforelse
                    </div>
                </div>
            </aside>

            <main class="be-main" id="main-content" tabindex="-1">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div
        x-show="toast.show"
        x-cloak
        x-transition
        class="be-toast"
        role="status"
        aria-live="polite"
        @click="toast.show = false"
    >
        <span x-text="toast.message"></span>
    </div>

    @livewireScripts
    <script>
        function erpShell() {
            return {
                toast: { show: false, message: '' },
                filterShortcuts(value) {
                    const q = (value || '').toLowerCase().trim();
                    this.$refs.shortcutNav.querySelectorAll('[data-label]').forEach((el) => {
                        el.style.display = !q || el.dataset.label.includes(q) ? '' : 'none';
                    });
                    if (!q) {
                        return;
                    }
                    this.$refs.shortcutNav.querySelectorAll('.be-sidebar__group').forEach((group) => {
                        if (group.style.display === 'none') {
                            return;
                        }
                        const data = group._x_dataStack && group._x_dataStack[0];
                        if (data) {
                            data.open = true;
                        }
                    });
                },
                handleShortcuts(e) {
                    const tag = (e.target.tagName || '').toLowerCase();
                    const typing = ['input', 'textarea', 'select'].includes(tag) || e.target.isContentEditable;

                    if (e.key === 'Escape') {
                        window.dispatchEvent(new CustomEvent('be-close-modal'));
                        return;
                    }

                    if (typing && !(e.ctrlKey || e.metaKey)) {
                        return;
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'f') {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('be-focus-search'));
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('be-save'));
                        this.showToast('Save shortcut ready (wired per form in later phases)');
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'n') {
                        e.preventDefault();
                        window.dispatchEvent(new CustomEvent('be-new'));
                        this.showToast('New shortcut ready (wired per module in later phases)');
                    }

                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'p') {
                        e.preventDefault();
                        window.print();
                    }

                    if (e.key === 'F2') {
                        e.preventDefault();
                        beOpenWorkspace('customers.index');
                    }
                    if (e.key === 'F3') {
                        e.preventDefault();
                        beOpenWorkspace('items.index');
                    }
                    if (e.key === 'F4') {
                        e.preventDefault();
                        beOpenWorkspace('payments.index');
                    }
                },
                handleMenuAction(entry) {
                    const action = entry && entry.action;
                    if (!action) return;
                    if (action === 'logout') {
                        document.querySelector('.be-utilitybar__user form')?.submit();
                        return;
                    }
                    if (action === 'toast') {
                        this.showToast(entry.message || entry.label || 'OK');
                        return;
                    }
                    if (action === 'focus-tabs') {
                        document.querySelector('.be-workspace__tabs')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        return;
                    }
                    const shell = document.querySelector('[data-workspace-shell]');
                    const wireId = shell?.closest('[wire\\:id]')?.getAttribute('wire:id') || shell?.getAttribute('wire:id');
                    const component = wireId && window.Livewire ? Livewire.find(wireId) : null;
                    if (!component) {
                        this.showToast('Open the workspace Home to use Window commands.');
                        return;
                    }
                    if (action === 'close-tab') component.call('closeTab', component.activeTabId);
                    if (action === 'close-others') component.call('closeOtherTabs', component.activeTabId);
                    if (action === 'close-all') component.call('closeAllTabs');
                    if (action === 'refresh-tab') component.call('refreshTab', component.activeTabId);
                },
                showToast(message) {
                    this.toast.message = message;
                    this.toast.show = true;
                    setTimeout(() => this.toast.show = false, 2500);
                }
            }
        }
    </script>
</body>
</html>
@endif
