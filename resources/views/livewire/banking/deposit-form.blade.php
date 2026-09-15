<div class="be-page be-invoice-page">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" variant="primary" wire:click="save">Save &amp; Post</x-erp.button>
        <x-erp.workspace-link route="deposits.index" class="be-btn">Cancel</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">
            Selected: <span class="num font-semibold">{{ number_format((float) $selectedTotal, 2) }}</span>
        </span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Make Deposits</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Deposit #</label>
                            <x-erp.input wire:model="number" class="be-input--combo" />
                            @error('number') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Deposit To</label>
                            <x-erp.select wire:model="bank_account_id" class="be-input--combo" :options="['' => 'Select…'] + $bankAccounts" />
                            @error('bank_account_id') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            <x-erp.input type="date" wire:model="deposit_date" class="be-input--combo" />
                            @error('deposit_date') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input wire:model="memo" class="be-input--combo" />
                        </div>
                    </div>
                </div>

                <p class="be-section-title mt-2 px-1">Payments to Deposit (Undeposited Funds)</p>
                @error('payments') <p class="be-invoice-error">{{ $message }}</p> @enderror

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid be-table--line-select">
                        <thead>
                            <tr>
                                <th style="width:40px">✓</th>
                                <th>DATE</th>
                                <th>PAYMENT #</th>
                                <th>RECEIVED FROM</th>
                                <th>PMT METHOD</th>
                                <th class="text-right">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payments as $payment)
                                <tr wire:key="pmt-{{ $payment->id }}">
                                    <td>
                                        <input type="checkbox" wire:model.live="selectedPayments.{{ $payment->id }}">
                                    </td>
                                    <td>{{ $payment->payment_date?->format('m/d/Y') }}</td>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>{{ $payment->customer?->display_name }}</td>
                                    <td>{{ $payment->method }}</td>
                                    <td class="num">{{ number_format((float) $payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No undeposited payments. Receive a customer payment first.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
