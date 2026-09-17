<div class="be-page be-invoice-page" x-data @be-focus-scan.window="$refs.scanInput?.focus()">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" variant="primary" wire:click="save">Receive &amp; Save</x-erp.button>
        <x-erp.workspace-link route="goods-receipts.index" class="be-btn">Cancel</x-erp.workspace-link>
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <span class="be-doc-toolbar__title">Receive Inventory</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">1. Vendor</label>
                        <select wire:model.live="vendor_id" class="be-input be-input--combo be-input--customer">
                            <option value="">Select vendor…</option>
                            @foreach ($vendors as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">2. Purchase Order</label>
                        <select wire:model.live="purchase_order_id" class="be-input be-input--combo" @disabled($vendor_id === '')>
                            @foreach ($poOptions as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('purchase_order_id') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Receive Inventory</h1>
                        <p class="be-field__hint text-[11px] text-gray-600">Select vendor → select PO → adjust receive qty → Save.</p>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Receipt Date</label>
                            <x-erp.input type="date" wire:model="receipt_date" class="be-input--combo" />
                            @error('receipt_date') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Receipt #</label>
                            <x-erp.input wire:model="number" class="be-input--combo" />
                            @error('number') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input wire:model="memo" class="be-input--combo" />
                        </div>
                    </div>
                </div>

                @if ($purchase_order_id !== '')
                    <x-erp.item-search-bar :show-add-line="true" />
                @endif
                @error('lines') <p class="be-invoice-error">{{ $message }}</p> @enderror

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid">
                        <thead>
                            <tr>
                                <th style="width:12%">ITEM CODE</th>
                                <th style="width:8%" class="text-right">ORDERED</th>
                                <th style="width:8%" class="text-right">RECV’D</th>
                                <th style="width:9%" class="text-right">RECEIVE</th>
                                <th>DESCRIPTION</th>
                                <th style="width:18%">ITEM</th>
                                <th class="text-right" style="width:10%">UNIT COST</th>
                                <th class="text-right" style="width:10%">AMOUNT</th>
                                <th style="width:4%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lines as $index => $line)
                                <tr wire:key="gr-line-{{ $index }}">
                                    <td>
                                        <x-erp.item-code-input
                                            wire:model.blur="lines.{{ $index }}.item_code"
                                            placeholder="Code…"
                                        />
                                    </td>
                                    <td class="num px-1">{{ number_format((float) ($line['ordered_qty'] ?? 0), 2) }}</td>
                                    <td class="num px-1">{{ number_format((float) ($line['previously_received'] ?? 0), 2) }}</td>
                                    <td>
                                        <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--grid" wire:model.live="lines.{{ $index }}.quantity" />
                                    </td>
                                    <td>
                                        <x-erp.input wire:model="lines.{{ $index }}.description" class="be-input--grid" />
                                    </td>
                                    <td>
                                        <x-erp.select wire:model.live="lines.{{ $index }}.item_id" class="be-input--grid" :options="['' => 'Select…'] + $itemOptions" />
                                        @error("lines.$index.item_id") <span class="be-field__error">{{ $message }}</span> @enderror
                                    </td>
                                    <td>
                                        <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--grid" wire:model.live="lines.{{ $index }}.rate" />
                                    </td>
                                    <td class="num">{{ number_format((float) ($line['amount'] ?? 0), 2) }}</td>
                                    <td class="text-right">
                                        <button type="button" class="be-link-btn" wire:click="removeLine({{ $index }})">×</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-2 py-3 text-[12px] text-gray-500">
                                        @if ($vendor_id === '')
                                            Select a vendor, then a purchase order to load items to receive.
                                        @elseif ($purchase_order_id === '')
                                            Select a purchase order to load remaining items.
                                        @else
                                            No open quantities on this PO.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="be-invoice-footer">
                    <div class="be-invoice-totals">
                        <div class="be-invoice-totals__row">
                            <span>Subtotal</span>
                            <strong>{{ number_format((float) $subtotal, 2) }}</strong>
                        </div>
                        <div class="be-invoice-totals__row be-invoice-totals__row--total">
                            <span>Total Received</span>
                            <strong>{{ number_format((float) $subtotal, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
