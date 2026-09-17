<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Purchase Order List" new-route="purchase-orders.create" new-label="New PO" :title="$orders->total().' purchase orders'" />

    @if (session('item_cost_alerts'))
        <div class="be-panel mb-2 border px-3 py-2" style="border-color:#c9a227;background:#fff8dc;">
            <div class="mb-1 text-[12px] font-semibold">PO cost changed — update sales price?</div>
            <ul class="m-0 list-disc pl-4 text-[12px]">
                @foreach (session('item_cost_alerts') as $alert)
                    <li>
                        {{ $alert['sku'] }}: cost {{ $alert['direction'] }}
                        {{ $alert['old_cost'] }} → {{ $alert['new_cost'] }}
                        (sales {{ $alert['sales_price'] }}
                        @if ($alert['suggested_sales_price'])
                            → suggest {{ $alert['suggested_sales_price'] }}
                        @endif)
                        <a class="be-link-btn" href="{{ route('items.edit', $alert['item_id']) }}">Open item</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="PO # / vendor…">
                <div class="be-field">
                    <label class="be-field__label">Status</label>
                    <x-erp.select
                        wire:model.live="status"
                        :options="[
                            'all' => 'All statuses',
                            'draft' => 'Draft',
                            'open' => 'Open',
                            'partial' => 'Partial',
                            'received' => 'Received',
                            'cancelled' => 'Cancelled',
                        ]"
                    />
                </div>
            </x-erp.look-for>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr
                            wire:key="po-{{ $order->id }}"
                            wire:click="selectLine({{ $order->id }})"
                            class="{{ $selectedLineId === $order->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $order->order_date?->format('m/d/Y') }}</td>
                            <td>{{ $order->number }}</td>
                            <td>{{ $order->vendor?->display_name }}</td>
                            <td><span class="be-badge">{{ $order->status }}</span></td>
                            <td class="num">{{ $order->lines_count }}</td>
                            <td class="num">{{ number_format((float) $order->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No purchase orders" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$orders" />
        </div>
    </div>
</div>
