<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" wire:click="save" type="button">OK</x-erp.button>
        <x-erp.workspace-link route="settings.index" class="be-btn">Preferences</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Company Information</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">My Company</h1>
            <p class="text-[11px] text-gray-600">Legal name, address, and contact info shown on invoices and statements.</p>
        </div>

        <div class="be-entity-dialog__body">
            <p class="be-section-title">Company Identity</p>
            <div class="be-form-grid">
                <div class="be-field">
                    <label class="be-field__label">Company Name *</label>
                    <x-erp.input wire:model="company_name" />
                    @error('company_name') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Legal Name</label>
                    <x-erp.input wire:model="legal_name" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Federal EIN</label>
                    <x-erp.input wire:model="federal_ein" placeholder="XX-XXXXXXX" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Website</label>
                    <x-erp.input wire:model="website" />
                </div>
            </div>

            <p class="be-section-title">Contact</p>
            <div class="be-form-grid">
                <div class="be-field">
                    <label class="be-field__label">Phone</label>
                    <x-erp.input wire:model="phone" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Fax</label>
                    <x-erp.input wire:model="fax" />
                </div>
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Email</label>
                    <x-erp.input type="email" wire:model="email" />
                    @error('email') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
            </div>

            <p class="be-section-title">Company Address</p>
            <div class="be-form-grid">
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Street 1</label>
                    <x-erp.input wire:model="address1" />
                </div>
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Street 2</label>
                    <x-erp.input wire:model="address2" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">City</label>
                    <x-erp.input wire:model="city" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">State</label>
                    <x-erp.input wire:model="state" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">ZIP</label>
                    <x-erp.input wire:model="zip" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Country</label>
                    <x-erp.input wire:model="country" />
                </div>
            </div>
        </div>
    </div>
</div>
