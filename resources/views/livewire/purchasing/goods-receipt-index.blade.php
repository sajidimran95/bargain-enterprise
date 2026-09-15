<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Receive Inventory" new-route="goods-receipts.create" new-label="Receive Inventory" :title="$receipts->total().' receipts'" />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Receipt # / vendor / memo…" />

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Vendor</th>
                        <th class="text-right">Lines</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($receipts as $receipt)
                        <tr
                            wire:key="gr-{{ $receipt->id }}"
                            wire:click="selectLine({{ $receipt->id }})"
                            class="{{ $selectedLineId === $receipt->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $receipt->receipt_date?->format('m/d/Y') }}</td>
                            <td>{{ $receipt->number }}</td>
                            <td>{{ $receipt->vendor?->display_name }}</td>
                            <td class="num">{{ $receipt->lines_count }}</td>
                            <td>{{ $receipt->memo }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><x-erp.empty-state title="No goods receipts" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$receipts" />
        </div>
    </div>
</div>
