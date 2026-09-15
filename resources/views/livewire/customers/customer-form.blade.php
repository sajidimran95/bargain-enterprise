<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button variant="primary" wire:click="save" type="button">Save</x-erp.button>
        <a href="{{ route('customers.index') }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">{{ $customer ? 'Edit Customer' : 'New Customer' }}</span>
    </x-erp.toolbar>

    <div class="be-panel">
        <div class="be-panel__header">
            <h1 class="be-panel__title">{{ $customer ? 'Edit Customer' : 'New Customer' }}</h1>
        </div>
        <div class="be-panel__body">
            <div class="be-form-grid">
                <div class="be-field">
                    <label class="be-field__label">Customer #</label>
                    <x-erp.input wire:model="customer_number" />
                    @error('customer_number') <span class="be-field__error">{{ $message }}</span> @enderror
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
                    @error('email') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Phone</label>
                    <x-erp.input wire:model="phone" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Alt. Phone</label>
                    <x-erp.input wire:model="alt_phone" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Fax</label>
                    <x-erp.input wire:model="fax" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Price Level</label>
                    <select wire:model="price_level_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($priceLevels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Tax Code</label>
                    <select wire:model="tax_code_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($taxCodes as $code)
                            <option value="{{ $code->id }}">{{ $code->code }} ({{ number_format((float) $code->rate, 2) }}%)</option>
                        @endforeach
                    </select>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Terms</label>
                    <x-erp.input wire:model="terms" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Credit Limit</label>
                    <x-erp.input wire:model="credit_limit" />
                </div>
            </div>

            <p class="be-section-title">Bill To</p>
            <div class="be-form-grid">
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Street 1</label>
                    <x-erp.input wire:model="bill_to_street1" />
                </div>
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Street 2</label>
                    <x-erp.input wire:model="bill_to_street2" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">City</label>
                    <x-erp.input wire:model="bill_to_city" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">State</label>
                    <x-erp.input wire:model="bill_to_state" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">ZIP</label>
                    <x-erp.input wire:model="bill_to_zip" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Country</label>
                    <x-erp.input wire:model="bill_to_country" />
                </div>
            </div>

            <p class="be-section-title">Notes &amp; Status</p>
            <div class="be-form-grid">
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Pinned Note</label>
                    <textarea wire:model="pinned_note" class="be-input" rows="3"></textarea>
                </div>
                <div class="be-field">
                    <label class="inline-flex items-center gap-2 text-[12px]">
                        <input type="checkbox" wire:model="online_payment_eligible"> Online payment eligible
                    </label>
                </div>
                <div class="be-field">
                    <label class="inline-flex items-center gap-2 text-[12px]">
                        <input type="checkbox" wire:model="is_active"> Active
                    </label>
                </div>
            </div>
        </div>
    </div>
</div>
