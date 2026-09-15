<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <a href="{{ route('banking.index') }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">New Bank Account</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">New Bank Account</h1>
        </div>
        <div class="be-panel__body">
            <div class="grid gap-3 md:grid-cols-2">
                <div>
                    <label class="be-label">Account Name *</label>
                    <x-erp.input wire:model="name" />
                    @error('name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Bank Name *</label>
                    <x-erp.input wire:model="bank_name" />
                    @error('bank_name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Account # Mask</label>
                    <x-erp.input wire:model="account_number_mask" placeholder="****1234" />
                </div>
                <div>
                    <label class="be-label">Opening Balance</label>
                    <x-erp.input type="number" step="0.01" wire:model="opening_balance" />
                </div>
                <div>
                    <label class="be-label">GL Number *</label>
                    <x-erp.input wire:model="gl_number" />
                    @error('gl_number') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">GL Name *</label>
                    <x-erp.input wire:model="gl_name" />
                </div>
            </div>
        </div>
    </div>
</div>
