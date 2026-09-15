<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Purchase Order List" new-route="purchase-orders.create" new-label="New PO" :title="$orders->total().' purchase orders'" />

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-2 flex flex-wrap gap-2">
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search PO # / vendor…" class="max-w-sm" />
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
