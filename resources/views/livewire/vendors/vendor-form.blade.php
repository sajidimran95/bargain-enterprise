<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button variant="primary" wire:click="save">Save</x-erp.button>
        <a href="{{ route('vendors.index') }}" class="be-btn">Cancel</a>
    </x-erp.toolbar>

    <div class="be-panel">
        <div class="be-panel__header">
            <h1 class="be-panel__title">{{ $vendor ? 'Edit Vendor' : 'New Vendor' }}</h1>
        </div>
        <div class="be-panel__body">
            <div class="be-form-grid">
                <div class="be-field">
                    <label class="be-field__label">Vendor #</label>
                    <x-erp.input wire:model="vendor_number" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Company Name *</label>
                    <x-erp.input wire:model="company_name" />
                    @error('company_name') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Display Name</label>
                    <x-erp.input wire:model="display_name" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">First Name</label>
                    <x-erp.input wire:model="first_name" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Last Name</label>
                    <x-erp.input wire:model="last_name" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Email</label>
                    <x-erp.input type="email" wire:model="email" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Phone</label>
                    <x-erp.input wire:model="phone" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Fax</label>
                    <x-erp.input wire:model="fax" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Terms</label>
                    <x-erp.input wire:model="terms" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Account #</label>
                    <x-erp.input wire:model="account_number" />
                </div>
            </div>

            <p class="be-section-title">Address</p>
            <div class="be-form-grid">
                <div class="be-field md:col-span-2"><label class="be-field__label">Street 1</label><x-erp.input wire:model="bill_from_street1" /></div>
                <div class="be-field md:col-span-2"><label class="be-field__label">Street 2</label><x-erp.input wire:model="bill_from_street2" /></div>
                <div class="be-field"><label class="be-field__label">City</label><x-erp.input wire:model="bill_from_city" /></div>
                <div class="be-field"><label class="be-field__label">State</label><x-erp.input wire:model="bill_from_state" /></div>
                <div class="be-field"><label class="be-field__label">ZIP</label><x-erp.input wire:model="bill_from_zip" /></div>
                <div class="be-field"><label class="be-field__label">Country</label><x-erp.input wire:model="bill_from_country" /></div>
            </div>

            <p class="be-section-title">Notes</p>
            <textarea wire:model="notes" class="be-input" rows="3"></textarea>
            <label class="mt-2 inline-flex items-center gap-2 text-[12px]">
                <input type="checkbox" wire:model="is_active"> Active
            </label>
        </div>
    </div>
</div>
