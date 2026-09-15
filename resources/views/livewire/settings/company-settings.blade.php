<div class="be-page be-entity-page">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" wire:click="save" type="button">OK</x-erp.button>
        <x-erp.workspace-link route="company.info" class="be-btn">My Company</x-erp.workspace-link>
        <x-erp.workspace-link route="lookups.index" class="be-btn">Lookups</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Preferences</span>
    </div>

    <div class="be-entity-dialog">
        <div class="be-entity-dialog__header">
            <h1 class="be-entity-dialog__title">Preferences</h1>
            <p class="text-[11px] text-gray-600">Application behavior — not company address or legal name.</p>
        </div>

        <div class="be-entity-dialog__body max-w-2xl">
            <p class="be-section-title">Items &amp; Inventory</p>
            <div class="be-field mb-3">
                <label class="be-field__label">Negative Stock Policy *</label>
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
                Allow manager override when policy is WARN/BLOCK
            </label>

            <p class="be-section-title">Master Data</p>
            <div class="be-side-panel__grid max-w-md">
                <x-erp.workspace-link route="lookups.index" :params="['activeType' => 'categories']">Item Categories</x-erp.workspace-link>
                <x-erp.workspace-link route="lookups.index" :params="['activeType' => 'item_types']">Item Types</x-erp.workspace-link>
                <x-erp.workspace-link route="lookups.index" :params="['activeType' => 'units']">Units of Measure</x-erp.workspace-link>
                <x-erp.workspace-link route="lookups.index" :params="['activeType' => 'tax_codes']">Tax Codes</x-erp.workspace-link>
                <x-erp.workspace-link route="lookups.index" :params="['activeType' => 'price_levels']">Price Levels</x-erp.workspace-link>
                <x-erp.workspace-link route="company.info">Company Information</x-erp.workspace-link>
            </div>
        </div>
    </div>
</div>
