<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <a href="{{ route($cancelRoute) }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">{{ $pageTitle }}</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">{{ $pageTitle }}</h1>
        </div>
        <div class="be-panel__body">
            <div class="grid gap-3 md:grid-cols-2">
                <div>
                    <label class="be-label">{{ $numberLabel }}</label>
                    @if (($numberField ?? null) === 'check_number')
                        <x-erp.input wire:model="check_number" />
                        @error('check_number') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    @else
                        <x-erp.input wire:model="number" />
                        @error('number') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                <div>
                    <label class="be-label">Bank Account *</label>
                    <x-erp.select wire:model="bank_account_id" :options="['' => 'Select…'] + $bankAccounts" />
                    @error('bank_account_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Date</label>
                    @if ($showPayee ?? false)
                        <x-erp.input type="date" wire:model="check_date" />
                    @else
                        <x-erp.input type="date" wire:model="deposit_date" />
                    @endif
                </div>
                <div>
                    <label class="be-label">Amount *</label>
                    @if (($amountField ?? null) === 'amount')
                        <x-erp.input type="number" step="0.01" min="0.01" wire:model="amount" />
                        @error('amount') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    @else
                        <x-erp.input type="number" step="0.01" min="0.01" wire:model="total" />
                        @error('total') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    @endif
                </div>
                @if ($showPayee ?? false)
                    <div>
                        <label class="be-label">Payee *</label>
                        <x-erp.input wire:model="payee" />
                        @error('payee') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Vendor (optional)</label>
                        <x-erp.select wire:model="vendor_id" :options="['' => 'Select…'] + ($vendors ?? [])" />
                    </div>
                @endif
                <div class="md:col-span-2">
                    <label class="be-label">Memo</label>
                    <x-erp.input wire:model="memo" />
                </div>
            </div>
        </div>
    </div>
</div>
