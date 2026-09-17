<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.toolbar>
        @can('create', App\Models\Item::class)
            <x-erp.workspace-link route="items.create" class="be-btn be-btn--primary">New</x-erp.workspace-link>
        @endcan
        <x-erp.button type="button" wire:click="focusSearch">Find</x-erp.button>
        @if ($selected)
            @can('update', $selected)
                <x-erp.workspace-link route="items.edit" :params="['item' => $selected->id]" :title="'Item: '.$selected->sku" class="be-btn">Edit</x-erp.workspace-link>
            @endcan
            @can('create', App\Models\Item::class)
                <x-erp.button wire:click="duplicateSelected">Duplicate</x-erp.button>
            @endcan
            @can('update', $selected)
                @if ($selected->is_active)
                    <x-erp.button wire:click="deactivateSelected" wire:confirm="Deactivate this item?">Make Inactive</x-erp.button>
                @else
                    <x-erp.button wire:click="activateSelected">Make Active</x-erp.button>
                @endif
            @endcan
            @can('delete', $selected)
                <x-erp.button variant="danger" wire:click="deleteSelected" wire:confirm="Permanently delete this item?">Delete</x-erp.button>
            @endcan
        @endif
        <x-erp.workspace-link route="lookups.index" class="be-btn">Lookups</x-erp.workspace-link>
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <span class="ml-auto text-[11px] text-gray-500">{{ $items->total() }} items</span>
    </x-erp.toolbar>

    <div class="be-panel m-0 rounded-none border-0 border-t" style="border-color: var(--be-border);">
        <div class="be-panel__body">
            <div class="mb-2 flex flex-wrap items-end gap-2">
                <div class="be-field">
                    <label class="be-field__label">Look for</label>
                    <x-erp.input x-ref="listSearch" wire:model="search" class="w-56" wire:keydown.enter="search" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Search in</label>
                    <x-erp.select
                        wire:model="searchField"
                        :options="[
                            'all' => 'All fields',
                            'sku' => 'SKU / Name',
                            'name' => 'Name',
                            'description' => 'Description',
                        ]"
                    />
                </div>
                <x-erp.button variant="primary" wire:click="search">Search</x-erp.button>
                <x-erp.button wire:click="resetSearch">Reset</x-erp.button>
                <label class="inline-flex items-center gap-1 text-[11px]">
                    <input type="checkbox" wire:model="searchWithinResults"> Search within results
                </label>
                <label class="inline-flex items-center gap-1 text-[11px]">
                    <input type="checkbox" wire:model.live="includeInactive"> Include inactive
                </label>
            </div>

            <div class="be-datatable-wrap" style="max-height: calc(100vh - 420px);">
                <table class="be-table be-table--line-select be-table--blue-select">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>DESCRIPTION</th>
                            <th>TYPE</th>
                            <th>ITEM TYPE</th>
                            <th class="text-right">TOTAL QTY ON HAND</th>
                            <th class="text-right">PRICE</th>
                            <th class="text-right">COST</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr
                                wire:key="item-{{ $item->id }}"
                                wire:click="selectItem({{ $item->id }})"
                                class="cursor-pointer {{ $selectedId === $item->id ? 'is-selected' : '' }}"
                                @dblclick="window.location.assign(@js(route('items.edit', $item)))"
                            >
                                <td>
                                    {{ $item->sku }}
                                    @unless ($item->is_active)
                                        <span class="be-badge">Inactive</span>
                                    @endunless
                                </td>
                                <td>{{ $item->sales_description ?: $item->name }}</td>
                                <td>{{ $item->type ?: 'Inventory Part' }}</td>
                                <td>{{ $item->itemType?->label ?? '—' }}</td>
                                <td class="num {{ (float) $item->on_hand < 0 ? 'text-red-700' : '' }}">
                                    {{ number_format((float) $item->on_hand, 2) }}
                                </td>
                                <td class="num">{{ number_format((float) $item->sales_price, 2) }}</td>
                                <td class="num">{{ number_format((float) $item->purchase_cost, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <x-erp.empty-state title="No items" message="Create an item to get started.">
                                        @can('create', App\Models\Item::class)
                                            <x-erp.workspace-link route="items.create" class="be-btn be-btn--primary mt-2 inline-flex">New Item</x-erp.workspace-link>
                                        @endcan
                                    </x-erp.empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="be-item-activity mt-2 border" style="border-color: var(--be-border);">
                <div class="flex items-center justify-between border-b px-2 py-1" style="border-color: var(--be-border); background: #ececec;">
                    <div class="text-[11px] font-semibold uppercase tracking-wide text-gray-700">
                        Activity
                        @if ($selected)
                            — {{ $selected->sku }}
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($selected)
                            <x-erp.workspace-link route="items.edit" :params="['item' => $selected->id]" class="be-link-btn text-[11px]">Open item</x-erp.workspace-link>
                        @endif
                        <button type="button" class="be-link-btn text-[11px]" wire:click="toggleActivity">
                            {{ $showActivity ? 'Hide' : 'Show' }}
                        </button>
                    </div>
                </div>

                @if ($showActivity)
                    <div class="be-datatable-wrap" style="max-height: 180px;">
                        <table class="be-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Event</th>
                                    <th class="text-right">In</th>
                                    <th class="text-right">Out</th>
                                    <th class="text-right">Balance</th>
                                    <th class="text-right">Old</th>
                                    <th class="text-right">New</th>
                                    <th>Memo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activity as $row)
                                    <tr wire:key="list-hist-{{ $row->id }}">
                                        <td class="whitespace-nowrap text-[11px]">{{ $row->occurred_at?->format('m/d/y g:i A') }}</td>
                                        <td class="text-[11px]">{{ $row->label() }}</td>
                                        <td class="num">{{ bccomp((string) $row->qty_in, '0', 4) > 0 ? number_format((float) $row->qty_in, 2) : '' }}</td>
                                        <td class="num">{{ bccomp((string) $row->qty_out, '0', 4) > 0 ? number_format((float) $row->qty_out, 2) : '' }}</td>
                                        <td class="num">{{ $row->balance_after !== null ? number_format((float) $row->balance_after, 2) : '' }}</td>
                                        <td class="num">{{ $row->old_value !== null ? number_format((float) $row->old_value, 2) : '' }}</td>
                                        <td class="num">{{ $row->new_value !== null ? number_format((float) $row->new_value, 2) : '' }}</td>
                                        <td class="text-[11px]">{{ $row->memo }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-2 py-2 text-[12px] text-gray-500">
                                            @if ($selected)
                                                No activity yet for this item.
                                            @else
                                                Select an item to view stock &amp; cost history.
                                            @endif
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="be-list-footer">
                <div class="relative" x-data="{ open: false }">
                    <button type="button" class="be-btn" @click="open = !open">Item ▾</button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute bottom-full left-0 z-20 mb-1 min-w-[160px] border bg-white shadow" style="border-color: var(--be-border-dark);">
                        @can('create', App\Models\Item::class)
                            <x-erp.workspace-link route="items.create" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">New</x-erp.workspace-link>
                        @endcan
                        @if ($selected)
                            @can('update', $selected)
                                <a href="{{ route('items.edit', $selected) }}" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Edit</a>
                            @endcan
                            @can('create', App\Models\Item::class)
                                <button type="button" class="block w-full px-3 py-1.5 text-left text-[12px] hover:bg-sky-50" wire:click="duplicateSelected" @click="open = false">Duplicate</button>
                            @endcan
                            @can('delete', $selected)
                                <button type="button" class="block w-full px-3 py-1.5 text-left text-[12px] text-red-700 hover:bg-sky-50" wire:click="deleteSelected" wire:confirm="Permanently delete this item?" @click="open = false">Delete</button>
                            @endcan
                        @endif
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button type="button" class="be-btn" @click="open = !open">Activities ▾</button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute bottom-full left-0 z-20 mb-1 min-w-[180px] border bg-white shadow" style="border-color: var(--be-border-dark);">
                        <button type="button" class="block w-full px-3 py-1.5 text-left text-[12px] hover:bg-sky-50" wire:click="openActivityHistory" @click="open = false">Item History</button>
                        <a href="{{ route('inventory.adjustments') }}" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Adjust Quantity</a>
                        <a href="{{ route('inventory.index') }}" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Inventory Center</a>
                    </div>
                </div>
                <div class="relative" x-data="{ open: false }">
                    <button type="button" class="be-btn" @click="open = !open">Reports ▾</button>
                    <div x-show="open" @click.outside="open = false" x-cloak class="absolute bottom-full left-0 z-20 mb-1 min-w-[180px] border bg-white shadow" style="border-color: var(--be-border-dark);">
                        <a href="{{ route('reports.inventory') }}" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Inventory / Stock</a>
                        <a href="{{ route('reports.sales-by-item') }}" class="block px-3 py-1.5 text-[12px] hover:bg-sky-50">Sales by Item</a>
                    </div>
                </div>
                <x-erp.button type="button" wire:click="exportExcel">Excel</x-erp.button>
                <x-erp.button type="button" disabled title="Coming soon">Attach</x-erp.button>
                <label class="ml-auto inline-flex items-center gap-1 text-[11px]">
                    <input type="checkbox" wire:model.live="includeInactive"> Include inactive
                </label>
            </div>

            <x-erp.pagination :paginator="$items" />
        </div>
    </div>
</div>
