<div class="be-page be-invoice-page">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" variant="primary" wire:click="save">Pay Selected Bills</x-erp.button>
        <x-erp.button type="button" wire:click="payAllOpen">Select All</x-erp.button>
        <x-erp.button type="button" wire:click="clearAllocations">Clear</x-erp.button>
        <x-erp.workspace-link route="vendor-payments.index" class="be-btn">Cancel</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Pay Bills</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Vendor</label>
                        <x-erp.select wire:model.live="vendor_id" class="be-input--combo be-input--customer" :options="['' => 'Select vendor…'] + $vendors" />
                        @error('vendor_id') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Pay Bills</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            <x-erp.input type="date" wire:model="payment_date" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Payment #</label>
                            <x-erp.input wire:model="payment_number" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Payment Amt</label>
                            <x-erp.input type="number" step="0.01" min="0" class="be-input--combo num font-semibold" wire:model.live="amount" />
                            @error('amount') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Method</label>
                            <x-erp.select wire:model.live="method" class="be-input--combo" :options="$paymentMethodOptions" />
                            @if ($paymentMethodOptions === [])
                                <p class="text-[11px] text-amber-700">
                                    <x-erp.workspace-link route="payment-methods.index" class="be-link-btn">Add payment methods</x-erp.workspace-link>
                                </p>
                            @endif
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Check No.</label>
                            <x-erp.input wire:model="check_number" class="be-input--combo" placeholder="Optional" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Account</label>
                            <x-erp.select wire:model="bank_account_id" class="be-input--combo" :options="['' => 'Select…'] + $accounts" />
                        </div>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input wire:model="memo" class="be-input--combo" />
                        </div>
                    </div>
                </div>

                <p class="be-section-title mt-2 px-1">Bills to Pay</p>
                @if ($openBills->isEmpty())
                    <p class="px-1 text-[12px] text-gray-500">Select a vendor with open bills. Check each bill to pay, or use Select All.</p>
                @else
                    <div class="be-invoice-grid-wrap">
                        <table class="be-table be-invoice-grid be-table--line-select">
                            <thead>
                                <tr>
                                    <th style="width:40px">✓</th>
                                    <th>DATE</th>
                                    <th>BILL #</th>
                                    <th>DUE DATE</th>
                                    <th class="text-right">OPEN BALANCE</th>
                                    <th class="text-right">AMT TO PAY</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($openBills as $bill)
                                    <tr
                                        wire:key="bill-alloc-{{ $bill->id }}"
                                        wire:click="selectLine({{ $bill->id }})"
                                        class="{{ $selectedLineId === $bill->id ? 'is-selected' : '' }}"
                                    >
                                        <td @click.stop>
                                            <input
                                                type="checkbox"
                                                wire:click="toggleBill({{ $bill->id }})"
                                                @checked(isset($selectedBills[$bill->id]) && $selectedBills[$bill->id])
                                            >
                                        </td>
                                        <td>{{ $bill->bill_date?->format('m/d/Y') }}</td>
                                        <td>{{ $bill->bill_number }}</td>
                                        <td class="{{ $bill->due_date && $bill->due_date->isPast() ? 'text-red-700' : '' }}">
                                            {{ $bill->due_date?->format('m/d/Y') ?: '—' }}
                                        </td>
                                        <td class="num">{{ number_format((float) $bill->balance_due, 2) }}</td>
                                        <td @click.stop>
                                            <x-erp.input
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                class="text-right be-input--grid"
                                                wire:model.live="allocations.{{ $bill->id }}"
                                                wire:change="syncAmountFromAllocations"
                                            />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="be-invoice-footer">
                        <div class="be-invoice-totals">
                            <div class="be-invoice-totals__row be-invoice-totals__row--total">
                                <span>Total to Pay</span>
                                <strong>{{ number_format((float) $this->allocationsTotal(), 2) }}</strong>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
