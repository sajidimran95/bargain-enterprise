<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <a href="{{ route('payments.index') }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">Receive Payment</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">Receive Payment</h1>
        </div>
        <div class="be-panel__body">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                <div>
                    <label class="be-label">Payment #</label>
                    <x-erp.input wire:model="payment_number" />
                    @error('payment_number') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Customer *</label>
                    <x-erp.select wire:model.live="customer_id" :options="['' => 'Select…'] + $customers" />
                    @error('customer_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Date</label>
                    <x-erp.input type="date" wire:model="payment_date" />
                </div>
                <div>
                    <label class="be-label">Amount *</label>
                    <x-erp.input type="number" step="0.01" min="0.01" wire:model="amount" />
                    @error('amount') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Method</label>
                    <x-erp.select
                        wire:model="method"
                        :options="['check' => 'Check', 'cash' => 'Cash', 'card' => 'Card', 'ach' => 'ACH', 'other' => 'Other']"
                    />
                </div>
                <div>
                    <label class="be-label">Reference</label>
                    <x-erp.input wire:model="reference" />
                </div>
                <div>
                    <label class="be-label">Deposit To</label>
                    <x-erp.select wire:model="deposit_to_account_id" :options="['' => 'Select…'] + $accounts" />
                </div>
                <div class="md:col-span-2">
                    <label class="be-label">Memo</label>
                    <x-erp.input wire:model="memo" />
                </div>
            </div>

            <p class="be-section-title mt-4">Apply to Invoices</p>
            @if ($openInvoices->isEmpty())
                <p class="text-sm text-gray-500">Select a customer with open invoices to allocate this payment.</p>
            @else
                <table class="be-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th class="text-right">Balance</th>
                            <th class="text-right">Amount to Apply</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($openInvoices as $invoice)
                            <tr wire:key="alloc-{{ $invoice->id }}">
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ $invoice->invoice_date?->format('m/d/Y') }}</td>
                                <td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td>
                                <td>
                                    <x-erp.input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        class="text-right"
                                        wire:model="allocations.{{ $invoice->id }}"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
