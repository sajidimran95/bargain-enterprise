<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar
        heading="Inventory"
        new-route="inventory.adjustments"
        :new-params="['new' => 1]"
        new-label="New Adjustment"
        :title="$items->total().' SKUs'"
    >
        <x-erp.workspace-link route="items.index" class="be-btn">Item List</x-erp.workspace-link>
    </x-erp.list-toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-2 flex flex-wrap gap-2">
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search SKU / name…" class="max-w-xs" />
                <x-erp.select
                    wire:model.live="stock"
                    :options="[
                        'all' => 'All stock',
                        'low' => 'At/below reorder',
                        'zero' => 'Zero on hand',
                        'negative' => 'Negative on hand',
                    ]"
                />
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th class="text-right">On Hand</th>
                        <th class="text-right">Avg Cost</th>
                        <th class="text-right">Reorder Min</th>
                        <th>Promotion</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr
                            wire:key="inv-{{ $item->id }}"
                            wire:click="selectLine({{ $item->id }})"
                            class="{{ $selectedLineId === $item->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->sales_description ?: $item->name }}</td>
                            <td>{{ $item->category?->code }}</td>
                            <td class="num {{ (float) $item->on_hand < 0 ? 'text-red-700' : '' }}">{{ number_format((float) $item->on_hand, 2) }}</td>
                            <td class="num">{{ number_format((float) $item->average_cost, 4) }}</td>
                            <td class="num">{{ number_format((float) ($item->reorder_min ?? 0), 2) }}</td>
                            <td>{{ $item->promotion ?: '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <x-erp.pagination :paginator="$items" />

            <p class="be-section-title">Recent Inventory Movements</p>
            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>SKU</th>
                        <th>Type</th>
                        <th class="text-right">In</th>
                        <th class="text-right">Out</th>
                        <th class="text-right">Balance</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recent as $tx)
                        <tr
                            wire:key="tx-{{ $tx->id }}"
                            wire:click="selectLine({{ $tx->id }})"
                            class="{{ $selectedLineId === $tx->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ optional($tx->occurred_at)->format('m/d/Y') }}</td>
                            <td>{{ $tx->item?->sku }}</td>
                            <td>{{ $tx->type }}</td>
                            <td class="num">{{ number_format((float) $tx->qty_in, 2) }}</td>
                            <td class="num">{{ number_format((float) $tx->qty_out, 2) }}</td>
                            <td class="num">{{ number_format((float) $tx->balance_after, 2) }}</td>
                            <td>{{ $tx->memo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
