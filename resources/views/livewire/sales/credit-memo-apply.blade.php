<div class="be-page">
    <x-erp.toolbar>
        <a href="{{ route('credit-memos.index') }}" class="be-btn">Credit Memo List</a>
        <span class="ml-auto text-[11px] text-gray-500">
            {{ $creditMemo->credit_number }} · Remaining
            <span class="num font-semibold">{{ number_format((float) $creditMemo->remaining_credit, 2) }}</span>
        </span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">Apply / Refund Credit — {{ $creditMemo->credit_number }}</h1>
            <span class="text-[12px] text-gray-600">{{ $creditMemo->customer?->display_name }}</span>
        </div>
        <div class="be-panel__body">
            @if (! $creditMemo->hasRemainingCredit())
                <p class="text-[12px] text-gray-600">This credit memo has no remaining credit.</p>
            @else
                <div class="mb-4 flex gap-2">
                    <button type="button" class="be-btn {{ $mode === 'apply' ? 'be-btn--primary' : '' }}" wire:click="$set('mode', 'apply')">Apply to Invoice</button>
                    <button type="button" class="be-btn {{ $mode === 'refund' ? 'be-btn--primary' : '' }}" wire:click="$set('mode', 'refund')">Give Refund</button>
                </div>

                @if ($mode === 'apply')
                    <p class="mb-2 text-[12px] text-gray-600">Apply remaining credit to open invoices for this customer. No cash moves; invoice balances are reduced.</p>
                    @error('allocations') <p class="mb-2 text-xs text-red-600">{{ $message }}</p> @enderror

                    <table class="be-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice #</th>
                                <th class="text-right">Balance Due</th>
                                <th class="text-right" style="width:140px">Amount to Apply</th>
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
                                            class="text-right"
                                            wire:model="allocations.{{ $invoice->id }}"
                                        />
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4">No open invoices for this customer.</td></tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <x-erp.button type="button" variant="primary" wire:click="applyCredit">Apply Credit</x-erp.button>
                    </div>
                @else
                    <p class="mb-2 text-[12px] text-gray-600">Pay out remaining credit as cash/check. Posts Dr AR / Cr Cash (or bank).</p>
                    <div class="grid max-w-xl gap-3 md:grid-cols-2">
                        <div>
                            <label class="be-label">Refund Date</label>
                            <x-erp.input type="date" wire:model="refund_date" />
                            @error('refund_date') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="be-label">Amount *</label>
                            <x-erp.input type="number" step="0.01" min="0.01" wire:model="refund_amount" />
                            @error('refund_amount') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="be-label">Method</label>
                            <x-erp.select
                                wire:model="refund_method"
                                :options="['cash' => 'Cash', 'check' => 'Check', 'other' => 'Other']"
                            />
                        </div>
                        <div>
                            <label class="be-label">Payout Account *</label>
                            <x-erp.select wire:model="refund_account_id" :options="['' => 'Select…'] + $payoutAccounts" />
                            @error('refund_account_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="be-label">Reference</label>
                            <x-erp.input wire:model="refund_reference" />
                        </div>
                        <div>
                            <label class="be-label">Memo</label>
                            <x-erp.input wire:model="refund_memo" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <x-erp.button type="button" variant="primary" wire:click="giveRefund">Give Refund</x-erp.button>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
