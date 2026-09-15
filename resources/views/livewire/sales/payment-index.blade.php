<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar
        heading="Customer Payment List"
        new-route="payments.create"
        new-label="Receive Payments"
        :title="$payments->total().' payments'"
    />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Payment # / customer…" />
            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th class="text-right">Amount</th>
                        <th>Applied To</th>
                        <th>Deposited</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr
                            wire:key="pmt-{{ $payment->id }}"
                            wire:click="selectLine({{ $payment->id }})"
                            class="{{ $selectedLineId === $payment->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $payment->payment_date?->format('m/d/Y') }}</td>
                            <td>{{ $payment->payment_number }}</td>
                            <td>{{ $payment->customer?->display_name }}</td>
                            <td>{{ $payment->method }}</td>
                            <td class="num">{{ number_format((float) $payment->amount, 2) }}</td>
                            <td>
                                {{ $payment->allocations->map(fn ($a) => $a->invoice?->invoice_number)->filter()->implode(', ') ?: '—' }}
                            </td>
                            <td>{{ $payment->deposited ? 'Yes' : 'No' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-erp.empty-state title="No payments" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$payments" />
        </div>
    </div>
</div>
