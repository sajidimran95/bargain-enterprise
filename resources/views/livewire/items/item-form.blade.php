<div
    class="be-page be-item-page"
    x-data
    @be-focus-barcode.window="$refs.barcodeInput?.focus(); $refs.barcodeInput?.select()"
>
    <div class="be-item-dialog">
        <div class="be-item-dialog__main">
            <div class="be-item-dialog__type-row">
                <div class="be-field be-item-dialog__type">
                    <label class="be-field__label">TYPE</label>
                    <x-erp.select
                        wire:model.live="type"
                        :options="[
                            'inventory_part' => 'Inventory Part',
                            'inventory_assembly' => 'Inventory Assembly',
                            'non_inventory' => 'Non-inventory Part',
                            'service' => 'Service',
                        ]"
                    />
                </div>
                <p class="be-item-dialog__type-help">{{ $typeHelp }}</p>
            </div>

            <div class="be-item-dialog__identity">
                <div class="be-field be-item-dialog__sku">
                    <label class="be-field__label">Item Name/Number</label>
                    <x-erp.input wire:model="sku" />
                    @error('sku') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>

                <div class="be-field be-item-dialog__subitem">
                    <label class="be-field__label">Subitem of</label>
                    <div class="be-item-dialog__subitem-row">
                        <input type="checkbox" wire:model.live="isSubitem" class="be-item-dialog__check">
                        <select wire:model="parent_id" class="be-input" @disabled(! $isSubitem)>
                            <option value="">—</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->sku }} — {{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="be-field be-item-dialog__mpn">
                    <label class="be-field__label">Manufacturer's Part Number</label>
                    <x-erp.input wire:model="manufacturer_part_number" />
                </div>
            </div>

            <div class="be-item-dialog__barcode">
                <div class="be-scan-bar be-scan-bar--compact">
                    <label class="be-field__label mb-0 shrink-0" for="item-barcode-input">Barcode / UPC</label>
                    <input
                        id="item-barcode-input"
                        type="text"
                        x-ref="barcodeInput"
                        class="be-input be-scan-input"
                        wire:model="barcode"
                        wire:keydown.enter.prevent="acceptBarcode"
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        inputmode="numeric"
                        placeholder="Scan with scanner or type UPC, then Enter"
                    />
                    <x-erp.button type="button" variant="primary" wire:click="acceptBarcode">Accept</x-erp.button>
                    <x-erp.button type="button" wire:click="useSkuAsBarcode" title="Copy Item Name/Number into barcode">Use SKU</x-erp.button>
                    <x-erp.button type="button" wire:click="clearBarcode" title="Clear barcode">Clear</x-erp.button>
                </div>
                @error('barcode') <span class="be-field__error">{{ $message }}</span> @enderror
                <p class="be-item-dialog__barcode-hint">Scanner and keyboard both work. Leave blank to default to Item Name/Number on save.</p>
            </div>

            <div class="be-item-dialog__uom">
                <span class="be-item-dialog__uom-label">UNIT OF MEASURE</span>
                <div class="be-item-dialog__uom-row">
                    <select wire:model="unit_of_measure_id" class="be-input be-item-dialog__uom-select">
                        <option value="">—</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    <x-erp.workspace-link route="lookups.index" class="be-btn be-item-dialog__uom-btn" title="Lookups">Enable…</x-erp.workspace-link>
                </div>
            </div>

            <div class="be-item-dialog__columns">
                <section class="be-item-dialog__col" aria-labelledby="purchase-info">
                    <h2 id="purchase-info" class="be-item-dialog__section">PURCHASE INFORMATION</h2>
                    <div class="be-field">
                        <label class="be-field__label">Description on Purchase Transactions</label>
                        <textarea wire:model="purchase_description" class="be-input be-item-dialog__desc" rows="4" spellcheck="true"></textarea>
                    </div>
                    <div class="be-field be-item-dialog__narrow">
                        <label class="be-field__label">Cost</label>
                        <x-erp.input wire:model="purchase_cost" class="be-item-dialog__money" />
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">COGS Account</label>
                        <select wire:model="cogs_account" class="be-input">
                            @foreach (['Cost of Goods Sold', 'Purchases'] as $account)
                                <option value="{{ $account }}" @selected($cogs_account === $account)>{{ $account }}</option>
                            @endforeach
                            @if ($cogs_account && ! in_array($cogs_account, ['Cost of Goods Sold', 'Purchases'], true))
                                <option value="{{ $cogs_account }}" selected>{{ $cogs_account }}</option>
                            @endif
                        </select>
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
                </section>

                <section class="be-item-dialog__col" aria-labelledby="sales-info">
                    <h2 id="sales-info" class="be-item-dialog__section">SALES INFORMATION</h2>
                    <div class="be-field">
                        <label class="be-field__label">Description on Sales Transactions</label>
                        <textarea wire:model="sales_description" class="be-input be-item-dialog__desc" rows="4" spellcheck="true"></textarea>
                    </div>
                    <div class="be-field be-item-dialog__narrow">
                        <label class="be-field__label">Sales Price</label>
                        <x-erp.input wire:model="sales_price" class="be-item-dialog__money" />
                    </div>
                    <div class="be-field be-item-dialog__narrow">
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
                        <select wire:model="income_account" class="be-input">
                            @foreach (['Sales', 'Merchandise Sales'] as $account)
                                <option value="{{ $account }}" @selected($income_account === $account)>{{ $account }}</option>
                            @endforeach
                            @if ($income_account && ! in_array($income_account, ['Sales', 'Merchandise Sales'], true))
                                <option value="{{ $income_account }}" selected>{{ $income_account }}</option>
                            @endif
                        </select>
                    </div>
                </section>
            </div>

            @if ($type === 'inventory_part' || $type === 'inventory_assembly')
                <section class="be-item-dialog__inventory" aria-labelledby="inventory-info">
                    <h2 id="inventory-info" class="be-item-dialog__section">INVENTORY INFORMATION</h2>
                    <div class="be-item-dialog__inventory-grid">
                        <div class="be-field">
                            <label class="be-field__label">Asset Account</label>
                            <select wire:model="asset_account" class="be-input">
                                <option value="Inventory Asset" @selected($asset_account === 'Inventory Asset')>Inventory Asset</option>
                                @if ($asset_account && $asset_account !== 'Inventory Asset')
                                    <option value="{{ $asset_account }}" selected>{{ $asset_account }}</option>
                                @endif
                            </select>
                        </div>
                        <div class="be-field be-item-dialog__narrow">
                            <label class="be-field__label">Reorder Point (Min)</label>
                            <x-erp.input wire:model="reorder_min" />
                        </div>
                        <div class="be-field be-item-dialog__narrow">
                            <label class="be-field__label">Max</label>
                            <x-erp.input wire:model="reorder_max" />
                        </div>
                    </div>

                    <div class="be-item-dialog__stock">
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">On Hand</span>
                            <span class="be-item-dialog__stock-value">{{ $item ? number_format((float) $item->on_hand, 0) : '0' }}</span>
                        </div>
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">Average Cost</span>
                            <span class="be-item-dialog__stock-value">{{ $item ? number_format((float) $item->average_cost, 2) : '0.00' }}</span>
                        </div>
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">On P.O.</span>
                            <span class="be-item-dialog__stock-value">{{ $item ? number_format((float) $item->on_po_qty, 0) : '0' }}</span>
                        </div>
                        <div class="be-item-dialog__stock-item">
                            <span class="be-item-dialog__stock-label">On Sales Order</span>
                            <span class="be-item-dialog__stock-value">{{ $item ? number_format((float) $item->on_so_qty, 0) : '0' }}</span>
                        </div>
                    </div>
                </section>
            @endif
        </div>

        <aside class="be-item-dialog__actions">
            <x-erp.button variant="primary" type="button" wire:click="save" class="be-item-dialog__ok">OK</x-erp.button>
            <x-erp.workspace-link route="items.index" class="be-btn be-item-dialog__side-btn">Cancel</x-erp.workspace-link>
            <x-erp.button type="button" wire:click="openNotes" class="be-item-dialog__side-btn">
                {{ $noteCount > 0 ? 'Notes ('.$noteCount.')' : 'New Note' }}
            </x-erp.button>
            <x-erp.button type="button" wire:click="openCustomFields" class="be-item-dialog__side-btn">Custom Fields</x-erp.button>
            <x-erp.button type="button" wire:click="checkSpelling" class="be-item-dialog__side-btn">Spelling</x-erp.button>

            <label class="be-item-dialog__inactive">
                <input type="checkbox" wire:model.live="itemInactive">
                Item is inactive
            </label>
        </aside>
    </div>

    @if ($showNotes)
        <div class="be-modal" role="dialog" aria-modal="true" aria-labelledby="item-notes-title">
            <div class="be-modal__backdrop" wire:click="closeNotes"></div>
            <div class="be-modal__panel be-modal__panel--md" @click.stop>
                <div class="be-modal__header">
                    <h2 id="item-notes-title" class="be-modal__title">Item Notes</h2>
                    <button type="button" class="be-modal__close" wire:click="closeNotes" aria-label="Close">&times;</button>
                </div>
                <div class="be-modal__body">
                    <div class="be-field mb-3">
                        <label class="be-field__label">{{ $editingNoteId ? 'Edit Note' : 'New Note' }}</label>
                        <textarea wire:model="noteBody" class="be-input" rows="4" spellcheck="true" placeholder="Type a note…"></textarea>
                        @error('noteBody') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-3 flex gap-2">
                        <x-erp.button type="button" variant="primary" wire:click="saveNote">{{ $editingNoteId ? 'Update Note' : 'Add Note' }}</x-erp.button>
                        @if ($editingNoteId)
                            <x-erp.button type="button" wire:click="cancelNoteEdit">Cancel Edit</x-erp.button>
                        @endif
                    </div>
                    <div class="space-y-2">
                        @forelse ($notes as $note)
                            <div class="border px-2 py-1.5" style="border-color: var(--be-border);" wire:key="item-note-{{ $note->id }}">
                                <div class="mb-1 flex items-center justify-between gap-2 text-[11px] text-gray-500">
                                    <span>{{ $note->user?->name ?? 'System' }} · {{ $note->created_at?->format('m/d/y g:i A') }}</span>
                                    <span class="flex gap-1">
                                        <button type="button" class="be-link-btn" wire:click="editNote({{ $note->id }})">Edit</button>
                                        <button type="button" class="be-link-btn" wire:click="deleteNote({{ $note->id }})" wire:confirm="Delete this note?">Delete</button>
                                    </span>
                                </div>
                                <div class="whitespace-pre-wrap text-[12px]">{{ $note->body }}</div>
                            </div>
                        @empty
                            <p class="text-[12px] text-gray-500">No notes yet.</p>
                        @endforelse
                    </div>
                </div>
                <div class="be-modal__footer">
                    <x-erp.button type="button" wire:click="closeNotes">Close</x-erp.button>
                </div>
            </div>
        </div>
    @endif

    @if ($showSpelling)
        <div class="be-modal" role="dialog" aria-modal="true" aria-labelledby="item-spelling-title">
            <div class="be-modal__backdrop" wire:click="closeSpelling"></div>
            <div class="be-modal__panel be-modal__panel--md" @click.stop>
                <div class="be-modal__header">
                    <h2 id="item-spelling-title" class="be-modal__title">Spelling &amp; Formatting</h2>
                    <button type="button" class="be-modal__close" wire:click="closeSpelling" aria-label="Close">&times;</button>
                </div>
                <div class="be-modal__body">
                    @if ($spellingIssues === [])
                        <p class="text-[12px] text-gray-700">No issues found in name, descriptions, promotion, or MPN.</p>
                    @else
                        <ul class="space-y-2 text-[12px]">
                            @foreach ($spellingIssues as $issue)
                                <li class="border px-2 py-1.5" style="border-color: var(--be-border);">
                                    <div class="font-semibold">{{ $issue['label'] }} — {{ $issue['issue'] }}</div>
                                    <div class="text-red-700 line-through">{{ $issue['original'] }}</div>
                                    <div class="text-green-700">{{ $issue['suggestion'] }}</div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="be-modal__footer">
                    @if ($spellingIssues !== [])
                        <x-erp.button type="button" variant="primary" wire:click="applySpellingFixes">Apply Fixes</x-erp.button>
                    @endif
                    <x-erp.button type="button" wire:click="closeSpelling">Close</x-erp.button>
                </div>
            </div>
        </div>
    @endif

    @if ($showCustomFields)
        <div class="be-modal" role="dialog" aria-modal="true" aria-labelledby="item-custom-fields-title">
            <div class="be-modal__backdrop" wire:click="closeCustomFields"></div>
            <div class="be-modal__panel be-modal__panel--md" @click.stop>
                <div class="be-modal__header">
                    <h2 id="item-custom-fields-title" class="be-modal__title">Custom Fields</h2>
                    <button type="button" class="be-modal__close" wire:click="closeCustomFields" aria-label="Close">&times;</button>
                </div>
                <div class="be-modal__body">
                    <p class="mb-3 text-[11px] text-gray-600">Tobacco / vape and other item attributes.</p>
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
                                @foreach ($itemTypes as $itemType)
                                    <option value="{{ $itemType->id }}">{{ $itemType->label }}</option>
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
                        <div class="be-field md:col-span-2">
                            <label class="be-field__label">Name / Title</label>
                            <x-erp.input wire:model="name" placeholder="Defaults to sales description or SKU" />
                        </div>
                    </div>
                </div>
                <div class="be-modal__footer">
                    <x-erp.button type="button" variant="primary" wire:click="closeCustomFields">OK</x-erp.button>
                    <x-erp.button type="button" wire:click="closeCustomFields">Cancel</x-erp.button>
                </div>
            </div>
        </div>
    @endif
</div>
