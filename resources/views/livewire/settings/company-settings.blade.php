<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button variant="primary" wire:click="save">Save</x-erp.button>
        <a href="{{ route('lookups.index') }}" class="be-btn">Manage Lookups</a>
        <a href="{{ route('customers.index') }}" class="be-btn">Customers</a>
        <a href="{{ route('items.index') }}" class="be-btn">Items</a>
        <span class="ml-auto text-[11px] text-gray-500">Company &amp; Inventory Policy</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">Settings</h1>
        </div>
        <div class="be-panel__body max-w-2xl">
            <p class="be-section-title">Company</p>
            <div class="be-field mb-3">
                <label class="be-field__label">Company Name *</label>
                <x-erp.input wire:model="company_name" />
                @error('company_name') <span class="be-field__error">{{ $message }}</span> @enderror
            </div>

            <p class="be-section-title">Inventory Negative Stock Policy</p>
            <div class="be-field mb-3">
                <label class="be-field__label">Policy *</label>
                <x-erp.select
                    wire:model="negative_policy"
                    :options="[
                        'ALLOW' => 'ALLOW — allow oversell freely',
                        'WARN' => 'WARN — allow with warning (default)',
                        'BLOCK' => 'BLOCK — prevent oversell',
                    ]"
                />
                @error('negative_policy') <span class="be-field__error">{{ $message }}</span> @enderror
            </div>
            <label class="mb-4 inline-flex items-center gap-2 text-[12px]">
                <input type="checkbox" wire:model="allow_manager_override">
                Allow manager override when policy is WARN/BLOCK (audited later)
            </label>

            <p class="be-section-title">Master Data Shortcuts</p>
            <div class="be-side-panel__grid max-w-md">
                <a href="{{ route('lookups.index', ['activeType' => 'categories']) }}">Item Categories</a>
                <a href="{{ route('lookups.index', ['activeType' => 'item_types']) }}">Item Types</a>
                <a href="{{ route('lookups.index', ['activeType' => 'units']) }}">Units of Measure</a>
                <a href="{{ route('lookups.index', ['activeType' => 'tax_codes']) }}">Tax Codes</a>
                <a href="{{ route('lookups.index', ['activeType' => 'price_levels']) }}">Price Levels</a>
            </div>
        </div>
    </div>
</div>
