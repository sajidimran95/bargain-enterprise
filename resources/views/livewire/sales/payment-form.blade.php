<div class="be-page be-invoice-page">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <x-erp.workspace-link route="payments.index" class="be-btn">Cancel</x-erp.workspace-link>
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <span class="be-doc-toolbar__title">Receive Payments</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Received From</label>
                        <x-erp.select wire:model.live="customer_id" class="be-input--combo be-input--customer" :options="['' => 'Select customer…'] + $customers" />
                        @error('customer_id') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Receive Payment</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            <x-erp.input type="date" wire:model="payment_date" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Payment #</label>
                            <x-erp.input wire:model="payment_number" class="be-input--combo" />
                            @error('payment_number') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Amount</label>
                            <x-erp.input type="number" step="0.01" min="0.01" wire:model="amount" class="be-input--combo" />
                            @error('amount') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Pmt Method</label>
                            <x-erp.select wire:model.live="method" class="be-input--combo" :options="$paymentMethodOptions" />
                            @if ($paymentMethodOptions === [])
                                <p class="text-[11px] text-amber-700">
                                    <x-erp.workspace-link route="payment-methods.index" class="be-link-btn">Add payment methods</x-erp.workspace-link>
                                </p>
                            @endif
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Reference #</label>
                            <x-erp.input wire:model="reference" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Deposit To</label>
                            <x-erp.select wire:model="deposit_to_account_id" class="be-input--combo" :options="['' => 'Select…'] + $accounts" />
                        </div>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input wire:model="memo" class="be-input--combo" />
                        </div>
                    </div>
                </div>

                <p class="be-section-title mt-2 px-1">Outstanding Invoices</p>
                @if ($openInvoices->isEmpty())
                    <p class="px-1 text-[12px] text-gray-500">Select a customer with open invoices to allocate this payment.</p>
                @else
                    <div class="be-invoice-grid-wrap">
                        <table class="be-table be-invoice-grid be-table--line-select">
                            <thead>
                                <tr>
                                    <th>INVOICE</th>
                                    <th>DATE</th>
                                    <th class="text-right">ORIG. AMT.</th>
                                    <th class="text-right">AMT. DUE</th>
                                    <th class="text-right">PAYMENT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($openInvoices as $invoice)
                                    <tr wire:key="alloc-{{ $invoice->id }}">
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->invoice_date?->format('m/d/Y') }}</td>
                                        <td class="num">{{ number_format((float) $invoice->total, 2) }}</td>
                                        <td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td>
                                        <td>
                                            <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--grid" wire:model="allocations.{{ $invoice->id }}" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
