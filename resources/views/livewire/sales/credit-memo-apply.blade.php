<div class="be-page be-invoice-page">
    <div class="be-doc-toolbar">
        <x-erp.workspace-link route="credit-memos.index" class="be-btn">Credit Memo List</x-erp.workspace-link>
        @if ($creditMemo->hasRemainingCredit())
            @if ($mode === 'apply')
                <x-erp.button type="button" variant="primary" wire:click="applyCredit">Apply Credit</x-erp.button>
            @else
                <x-erp.button type="button" variant="primary" wire:click="giveRefund">Give Refund</x-erp.button>
            @endif
        @endif
        <span class="be-doc-toolbar__title">
            {{ $creditMemo->credit_number }} · Remaining
            <span class="num font-semibold">{{ number_format((float) $creditMemo->remaining_credit, 2) }}</span>
        </span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Customer</label>
                        <input type="text" class="be-input be-input--combo be-input--customer" readonly value="{{ $creditMemo->customer?->display_name }}">
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Apply Credits</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Credit #</label>
                            <input type="text" class="be-input be-input--combo" readonly value="{{ $creditMemo->credit_number }}">
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Remaining</label>
                            <input type="text" class="be-input be-input--combo num" readonly value="{{ number_format((float) $creditMemo->remaining_credit, 2) }}">
                        </div>
                    </div>
                </div>

                @if (! $creditMemo->hasRemainingCredit())
                    <p class="px-1 text-[12px] text-gray-600">This credit memo has no remaining credit.</p>
                @else
                    <div class="be-apply-tabs">
                        <button type="button" class="be-apply-tabs__btn {{ $mode === 'apply' ? 'is-active' : '' }}" wire:click="$set('mode', 'apply')">Apply to Invoice</button>
                        <button type="button" class="be-apply-tabs__btn {{ $mode === 'refund' ? 'is-active' : '' }}" wire:click="$set('mode', 'refund')">Give Refund</button>
                    </div>

                    @if ($mode === 'apply')
                        <p class="mb-2 px-1 text-[12px] text-gray-600">Apply remaining credit to open invoices. No cash moves; invoice balances are reduced.</p>
                        @error('allocations') <p class="be-invoice-error">{{ $message }}</p> @enderror

                        <div class="be-invoice-grid-wrap">
                            <table class="be-table be-invoice-grid be-table--line-select">
                                <thead>
                                    <tr>
                                        <th>DATE</th>
                                        <th>INVOICE #</th>
                                        <th class="text-right">AMT. DUE</th>
                                        <th class="text-right" style="width:140px">CREDIT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $invoice)
                                        <tr wire:key="inv-{{ $invoice->id }}">
                                            <td>{{ $invoice->invoice_date?->format('m/d/Y') }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td>
                                            <td>
                                                <x-erp.input
                                                    type="number"
                                                    step="0.01"
                                                    min="0"
                                                    class="text-right be-input--grid"
                                                    wire:model="allocations.{{ $invoice->id }}"
                                                />
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">No open invoices for this customer.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="mb-2 px-1 text-[12px] text-gray-600">Pay out remaining credit as cash/check.</p>
                        <div class="be-invoice-midrow__right" style="max-width: 520px; margin: 0 auto 0 0;">
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Refund Date</label>
                                <x-erp.input type="date" wire:model="refund_date" class="be-input--combo" />
                                @error('refund_date') <span class="be-field__error">{{ $message }}</span> @enderror
                            </div>
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Amount</label>
                                <x-erp.input type="number" step="0.01" min="0.01" wire:model="refund_amount" class="be-input--combo" />
                                @error('refund_amount') <span class="be-field__error">{{ $message }}</span> @enderror
                            </div>
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Method</label>
                                <x-erp.select wire:model="refund_method" class="be-input--combo" :options="['cash' => 'Cash', 'check' => 'Check', 'other' => 'Other']" />
                            </div>
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Payout Account</label>
                                <x-erp.select wire:model="refund_account_id" class="be-input--combo" :options="['' => 'Select…'] + $payoutAccounts" />
                                @error('refund_account_id') <span class="be-field__error">{{ $message }}</span> @enderror
                            </div>
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Reference</label>
                                <x-erp.input wire:model="refund_reference" class="be-input--combo" />
                            </div>
                            <div class="be-field">
                                <label class="be-field__label be-field__label--caps">Memo</label>
                                <x-erp.input wire:model="refund_memo" class="be-input--combo" />
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
