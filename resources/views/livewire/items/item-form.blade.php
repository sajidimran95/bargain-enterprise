<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button variant="primary" wire:click="save">OK</x-erp.button>
        <a href="{{ route('items.index') }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">{{ $item ? 'Edit Item' : 'New Item' }}</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">{{ $item ? 'Edit Item' : 'New Item' }}</h1>
            <label class="inline-flex items-center gap-1 text-[11px]">
                <input type="checkbox" wire:model="is_active"> Item is active
            </label>
        </div>
        <div class="be-panel__body">
            <div class="be-form-grid">
                <div class="be-field">
                    <label class="be-field__label">Type</label>
                    <x-erp.select wire:model="type" :options="['inventory_part' => 'Inventory Part']" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Item Name/Number (SKU) *</label>
                    <x-erp.input wire:model="sku" />
                    @error('sku') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Barcode / UPC</label>
                    <x-erp.input wire:model="barcode" placeholder="Scan or type barcode (defaults to SKU)" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Name / Title</label>
                    <x-erp.input wire:model="name" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Subitem of</label>
                    <select wire:model="parent_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->sku }} — {{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Manufacturer's Part Number</label>
                    <x-erp.input wire:model="manufacturer_part_number" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Unit of Measure</label>
                    <select wire:model="unit_of_measure_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <p class="be-section-title">Purchase Information</p>
            <div class="be-form-grid">
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Description on Purchase Transactions</label>
                    <textarea wire:model="purchase_description" class="be-input" rows="2"></textarea>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Cost</label>
                    <x-erp.input wire:model="purchase_cost" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">COGS Account</label>
                    <x-erp.input wire:model="cogs_account" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Preferred Vendor</label>
                    <select wire:model="preferred_vendor_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->display_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <p class="be-section-title">Sales Information</p>
            <div class="be-form-grid">
                <div class="be-field md:col-span-2">
                    <label class="be-field__label">Description on Sales Transactions</label>
                    <textarea wire:model="sales_description" class="be-input" rows="2"></textarea>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Sales Price</label>
                    <x-erp.input wire:model="sales_price" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Tax Code</label>
                    <select wire:model="tax_code_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($taxCodes as $code)
                            <option value="{{ $code->id }}">{{ $code->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Income Account</label>
                    <x-erp.input wire:model="income_account" />
                </div>
            </div>

            <p class="be-section-title">Inventory Information</p>
            <div class="be-form-grid">
                <div class="be-field">
                    <label class="be-field__label">Asset Account</label>
                    <x-erp.input wire:model="asset_account" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Reorder Point (Min)</label>
                    <x-erp.input wire:model="reorder_min" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Max</label>
                    <x-erp.input wire:model="reorder_max" />
                </div>
                @if ($item)
                    <div class="be-field">
                        <label class="be-field__label">On Hand</label>
                        <x-erp.input :value="number_format((float) $item->on_hand, 4)" disabled />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Average Cost</label>
                        <x-erp.input :value="number_format((float) $item->average_cost, 4)" disabled />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">On P.O.</label>
                        <x-erp.input :value="number_format((float) $item->on_po_qty, 4)" disabled />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">On Sales Order</label>
                        <x-erp.input :value="number_format((float) $item->on_so_qty, 4)" disabled />
                    </div>
                @endif
            </div>

            <p class="be-section-title">Tobacco / Vape Attributes</p>
            <div class="be-form-grid">
                <div class="be-field">
                    <label class="be-field__label">Category Code</label>
                    <select wire:model="item_category_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->code }} — {{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Item Type</label>
                    <select wire:model="item_type_id" class="be-input">
                        <option value="">—</option>
                        @foreach ($itemTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="be-field">
                    <label class="be-field__label">Items Per Container</label>
                    <x-erp.input wire:model="items_per_container" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Promotion</label>
                    <x-erp.input wire:model="promotion" />
                </div>
            </div>
        </div>
    </div>
</div>
