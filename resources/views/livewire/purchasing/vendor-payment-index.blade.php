<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Pay Bills" new-route="vendor-payments.create" new-label="Pay Bills" :title="$payments->total().' payments'" />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Payment # / vendor…" />

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Vendor</th>
                        <th>Method</th>
                        <th class="text-right">Amount</th>
                        <th class="text-right">Applied</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr
                            wire:key="vpay-{{ $payment->id }}"
                            wire:click="selectLine({{ $payment->id }})"
                            class="{{ $selectedLineId === $payment->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $payment->payment_date?->format('m/d/Y') }}</td>
                            <td>{{ $payment->payment_number }}</td>
                            <td>{{ $payment->vendor?->display_name }}</td>
                            <td>{{ $payment->method }}</td>
                            <td class="num">{{ number_format((float) $payment->amount, 2) }}</td>
                            <td class="num">{{ $payment->allocations_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No vendor payments" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$payments" />
        </div>
    </div>
</div>
