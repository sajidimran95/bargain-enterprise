<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button type="button" variant="primary" wire:click="save">Pay Selected Bills</x-erp.button>
        <x-erp.button type="button" wire:click="payAllOpen">Select All / Pay All</x-erp.button>
        <x-erp.button type="button" wire:click="clearAllocations">Clear</x-erp.button>
        <a href="{{ route('vendor-payments.index') }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">Pay Bills</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">Pay Bills</h1>
        </div>
        <div class="be-panel__body">
            <div class="be-bill-header">
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label class="be-label">Payment #</label>
                        <x-erp.input wire:model="payment_number" />
                    </div>
                    <div>
                        <label class="be-label">Vendor *</label>
                        <x-erp.select wire:model.live="vendor_id" :options="['' => 'Select…'] + $vendors" />
                        @error('vendor_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Date</label>
                        <x-erp.input type="date" wire:model="payment_date" />
                    </div>
                    <div>
                        <label class="be-label">Payment Amount</label>
                        <x-erp.input type="number" step="0.01" min="0" class="num font-semibold" wire:model.live="amount" />
                        @error('amount') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Method</label>
                        <x-erp.select wire:model="method" :options="['check' => 'Check', 'ach' => 'ACH', 'cash' => 'Cash', 'other' => 'Other']" />
                    </div>
                    <div>
                        <label class="be-label">Check No. / Ref</label>
                        <x-erp.input wire:model="check_number" placeholder="Optional" />
                    </div>
                    <div>
                        <label class="be-label">Account</label>
                        <x-erp.select wire:model="bank_account_id" :options="['' => 'Select…'] + $accounts" />
                    </div>
                    <div>
                        <label class="be-label">Memo</label>
                        <x-erp.input wire:model="memo" />
                    </div>
                </div>
            </div>

            <p class="be-section-title mt-2">Bills to Pay</p>
            @if ($openBills->isEmpty())
                <p class="text-sm text-gray-500">Select a vendor with open bills. Check each bill to pay, or use Select All.</p>
            @else
                <table class="be-table be-table--line-select">
                    <thead>
                        <tr>
                            <th style="width:40px">Pay</th>
                            <th>Date</th>
                            <th>Bill #</th>
                            <th>Due Date</th>
                            <th class="text-right">Open Balance</th>
                            <th class="text-right">Amt to Pay</th>
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
                                        class="text-right"
                                        wire:model.live="allocations.{{ $bill->id }}"
                                        wire:change="syncAmountFromAllocations"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right font-semibold">Total to Pay</td>
                            <td class="num font-semibold">{{ number_format((float) $this->allocationsTotal(), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @endif

            <div class="mt-4 flex justify-end gap-2">
                <a href="{{ route('vendor-payments.index') }}" class="be-btn">Clear</a>
                <x-erp.button type="button" variant="primary" wire:click="save">Pay & Close</x-erp.button>
            </div>
        </div>
    </div>
</div>
