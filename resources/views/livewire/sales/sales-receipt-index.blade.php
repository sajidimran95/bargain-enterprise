<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Sales Receipt List" new-route="sales-receipts.create" new-label="New Sales Receipt" :title="$receipts->total().' sales receipts'" />

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-2">
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search receipt # / customer…" class="max-w-sm" />
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $receipt)
                        <tr
                            wire:key="sr-{{ $receipt->id }}"
                            wire:click="selectLine({{ $receipt->id }})"
                            class="{{ $selectedLineId === $receipt->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $receipt->receipt_date?->format('m/d/Y') }}</td>
                            <td>{{ $receipt->number }}</td>
                            <td>{{ $receipt->customer?->display_name }}</td>
                            <td>{{ $receipt->payment_method }}</td>
                            <td class="num">{{ $receipt->lines_count }}</td>
                            <td class="num">{{ number_format((float) $receipt->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No sales receipts" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$receipts" />
        </div>
    </div>
</div>
