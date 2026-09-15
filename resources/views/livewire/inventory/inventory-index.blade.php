<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar
        heading="Inventory Stock Status"
        new-route="inventory.adjustments"
        :new-params="['new' => 1]"
        new-label="New Adjustment"
        :title="$items->total().' SKUs'"
    >
        <x-erp.workspace-link route="items.index" class="be-btn">Item List</x-erp.workspace-link>
        <x-erp.workspace-link route="reports.inventory" class="be-btn">MSA Inventory</x-erp.workspace-link>
    </x-erp.list-toolbar>

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="SKU / name…">
                <div class="be-field">
                    <label class="be-field__label">Stock</label>
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
            </x-erp.look-for>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>DESCRIPTION</th>
                        <th>TYPE</th>
                        <th class="text-right">QTY</th>
                        <th class="text-right">PRICE</th>
                        <th class="text-right">COST</th>
                        <th class="text-right">REORDER</th>
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
                            <td>{{ $item->type ?: $item->category?->code }}</td>
                            <td class="num {{ (float) $item->on_hand < 0 ? 'text-red-700' : '' }}">{{ number_format((float) $item->on_hand, 2) }}</td>
                            <td class="num">{{ number_format((float) $item->sales_price, 2) }}</td>
                            <td class="num">{{ number_format((float) ($item->average_cost ?: 0), 4) }}</td>
                            <td class="num">{{ number_format((float) ($item->reorder_min ?? 0), 2) }}</td>
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
