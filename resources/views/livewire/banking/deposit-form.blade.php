<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button type="button" variant="primary" wire:click="save">Save &amp; Post</x-erp.button>
        <a href="{{ route('deposits.index') }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">
            Selected: <span class="num font-semibold">{{ number_format((float) $selectedTotal, 2) }}</span>
        </span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">Make Deposit</h1>
        </div>
        <div class="be-panel__body">
            <div class="mb-4 grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="be-label">Deposit #</label>
                    <x-erp.input wire:model="number" />
                    @error('number') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Deposit To *</label>
                    <x-erp.select wire:model="bank_account_id" :options="['' => 'Select…'] + $bankAccounts" />
                    @error('bank_account_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Date</label>
                    <x-erp.input type="date" wire:model="deposit_date" />
                    @error('deposit_date') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Memo</label>
                    <x-erp.input wire:model="memo" />
                </div>
            </div>

            <p class="mb-2 text-[12px] text-gray-600">
                Select payments from <strong>Undeposited Funds</strong>. Posting moves cash to the bank (Dr Bank / Cr 1050).
            </p>
            @error('payments') <p class="mb-2 text-xs text-red-600">{{ $message }}</p> @enderror

            <table class="be-table">
                <thead>
                    <tr>
                        <th style="width:40px"></th>
                        <th>Date</th>
                        <th>Payment #</th>
                        <th>Customer</th>
                        <th>Method</th>
                        <th class="text-right">Amount</th>
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
