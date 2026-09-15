<div class="be-page be-invoice-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" variant="primary" wire:click="createInvoices">Create Invoices</x-erp.button>
        <x-erp.button type="button" wire:click="selectAllVisible">Select All</x-erp.button>
        <x-erp.button type="button" wire:click="clearSelection">Clear</x-erp.button>
        <x-erp.workspace-link route="sales-orders.index" class="be-btn">Sales Orders</x-erp.workspace-link>
        <x-erp.workspace-link route="sales-orders.create" class="be-btn">New SO</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Sales Order Fulfillment Worksheet</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Sales Order Fulfillment</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Look for</label>
                            <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" class="be-input--combo" placeholder="SO # / customer…" />
                        </div>
                    </div>
                </div>

                <p class="be-section-title mt-2 px-1">Open Sales Orders</p>

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid be-table--line-select">
                        <thead>
                            <tr>
                                <th style="width:40px">✓</th>
                                <th>DATE</th>
                                <th>SO #</th>
                                <th>CUSTOMER</th>
                                <th class="text-right">LINES</th>
                                <th class="text-right">TOTAL</th>
                                <th>STOCK CHECK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $short = $order->lines->filter(fn ($line) => $line->item && (float) $line->item->on_hand < (float) $line->quantity)->count();
                                @endphp
                                <tr wire:key="so-ful-{{ $order->id }}">
                                    <td>
                                        <input
                                            type="checkbox"
                                            wire:click="toggleOrder({{ $order->id }})"
                                            @checked(! empty($selectedOrders[$order->id]))
                                        >
                                    </td>
                                    <td>{{ $order->order_date?->format('m/d/Y') }}</td>
                                    <td>{{ $order->number }}</td>
                                    <td>{{ $order->customer?->display_name }}</td>
                                    <td class="num">{{ $order->lines_count }}</td>
                                    <td class="num">{{ number_format((float) $order->total, 2) }}</td>
                                    <td class="{{ $short > 0 ? 'text-red-700' : 'text-green-700' }}">
                                        {{ $short > 0 ? $short.' short' : 'OK' }}
                                    </td>
                                </tr>
                                @foreach ($order->lines as $line)
                                    <tr class="be-fulfill-line" wire:key="so-line-{{ $line->id }}">
                                        <td></td>
                                        <td colspan="2" class="pl-4 text-gray-600">{{ $line->item?->sku }}</td>
                                        <td colspan="2">{{ $line->description ?: $line->item?->name }}</td>
                                        <td class="num">{{ number_format((float) $line->quantity, 2) }} ordered</td>
                                        <td class="num {{ $line->item && (float) $line->item->on_hand < (float) $line->quantity ? 'text-red-700' : '' }}">
                                            {{ number_format((float) ($line->item?->on_hand ?? 0), 2) }} on hand
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr><td colspan="7">No open sales orders to fulfill.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 px-1">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</div>
