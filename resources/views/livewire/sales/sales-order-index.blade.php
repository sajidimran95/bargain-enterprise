<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Sales Order List" new-route="sales-orders.create" new-label="New Sales Order" :title="$orders->total().' sales orders'">
        <x-erp.workspace-link route="sales-orders.fulfillment" class="be-btn">Create Invoices from SO…</x-erp.workspace-link>
    </x-erp.list-toolbar>

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="SO # / customer…">
                <div class="be-field">
                    <label class="be-field__label">Status</label>
                    <x-erp.select
                        wire:model.live="status"
                        :options="[
                            'all' => 'All statuses',
                            'draft' => 'Draft',
                            'open' => 'Open',
                            'invoiced' => 'Invoiced',
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
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr
                            wire:key="so-{{ $order->id }}"
                            wire:click="selectLine({{ $order->id }})"
                            @if ($order->status !== 'invoiced')
                                wire:dblclick="openEdit({{ $order->id }})"
                            @endif
                            class="{{ $selectedLineId === $order->id ? 'is-selected' : '' }} cursor-pointer"
                        >
                            <td>{{ $order->order_date?->format('m/d/Y') }}</td>
                            <td>{{ $order->number }}</td>
                            <td>{{ $order->customer?->display_name }}</td>
                            <td><span class="be-badge">{{ $order->status }}</span></td>
                            <td class="num">{{ $order->lines_count }}</td>
                            <td class="num">{{ number_format((float) $order->total, 2) }}</td>
                            <td class="whitespace-nowrap">
                                @if ($order->status !== 'invoiced')
                                    <x-erp.workspace-link route="sales-orders.edit" :params="['salesOrder' => $order->id]" :title="'SO: '.$order->number" class="be-link-btn" @click.stop>
                                        Edit
                                    </x-erp.workspace-link>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-erp.empty-state title="No sales orders" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$orders" />
        </div>
    </div>
</div>
