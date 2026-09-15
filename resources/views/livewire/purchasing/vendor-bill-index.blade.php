<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Vendor Bill List" new-route="vendor-bills.create" new-label="Enter Bills" :title="'Open AP: '.number_format((float) $openAp, 2).' · '.$bills->total().' bills'" />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Bill # / vendor…">
                <div class="be-field">
                    <label class="be-field__label">Status</label>
                    <x-erp.select
                        wire:model.live="status"
                        :options="[
                            'all' => 'All statuses',
                            'open' => 'Open',
                            'partial' => 'Partial',
                            'paid' => 'Paid',
                        ]"
                    />
                </div>
            </x-erp.look-for>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bill #</th>
                        <th>Vendor</th>
                        <th>Status</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bills as $bill)
                        <tr
                            wire:key="vb-{{ $bill->id }}"
                            wire:click="selectLine({{ $bill->id }})"
                            class="{{ $selectedLineId === $bill->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $bill->bill_date?->format('m/d/Y') }}</td>
                            <td>{{ $bill->bill_number }}</td>
                            <td>{{ $bill->vendor?->display_name }}</td>
                            <td><span class="be-badge">{{ $bill->status }}</span></td>
                            <td class="num">{{ $bill->lines_count }}</td>
                            <td class="num">{{ number_format((float) $bill->total, 2) }}</td>
                            <td class="num">{{ number_format((float) $bill->balance_due, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-erp.empty-state title="No vendor bills" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$bills" />
        </div>
    </div>
</div>
