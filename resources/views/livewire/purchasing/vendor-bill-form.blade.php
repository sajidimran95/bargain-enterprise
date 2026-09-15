<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <x-erp.button type="button" wire:click="addLine">New Line</x-erp.button>
        <x-erp.button type="button" wire:click="selectPurchaseOrder">Select PO</x-erp.button>
        <a href="{{ route('vendor-payments.create') }}" class="be-btn">Pay Bill</a>
        <a href="{{ route('vendor-bills.index') }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">Enter Bills</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="be-bill-header">
                <div class="mb-2 flex flex-wrap items-center gap-4">
                    <label class="inline-flex items-center gap-1 text-[12px] font-semibold">
                        <input type="radio" wire:model.live="docType" value="bill"> Bill
                    </label>
                    <label class="inline-flex items-center gap-1 text-[12px] font-semibold">
                        <input type="radio" wire:model.live="docType" value="credit"> Credit
                    </label>
                    <span class="text-[16px] font-semibold text-gray-700">{{ $docType === 'credit' ? 'Credit' : 'Bill' }}</span>
                </div>

                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                    <div class="lg:col-span-2">
                        <label class="be-label">Vendor</label>
                        <x-erp.select wire:model.live="vendor_id" :options="['' => 'Select vendor…'] + $vendors" />
                        @error('vendor_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                        @if ($selectedVendor)
                            <div class="mt-1 whitespace-pre-line text-[11px] text-gray-600">{{ implode("\n", $selectedVendor->billFromLines()) }}</div>
                        @endif
                    </div>
                    <div>
                        <label class="be-label">Date</label>
                        <x-erp.input type="date" wire:model="bill_date" />
                    </div>
                    <div>
                        <label class="be-label">Bill Due</label>
                        <x-erp.input type="date" wire:model="due_date" />
                    </div>
                    <div>
                        <label class="be-label">Ref. No.</label>
                        <x-erp.input wire:model="ref_no" />
                    </div>
                    <div>
                        <label class="be-label">{{ $docType === 'credit' ? 'Credit #' : 'Bill #' }}</label>
                        <x-erp.input wire:model="bill_number" />
                        @error('bill_number') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Amount Due</label>
                        <x-erp.input class="num font-semibold" value="{{ number_format((float) $amountDue, 2, '.', '') }}" readonly />
                    </div>
                    <div>
                        <label class="be-label">Terms</label>
                        <x-erp.select wire:model="terms" :options="['Due on receipt' => 'Due on receipt', 'Net 15' => 'Net 15', 'Net 30' => 'Net 30', 'Net 60' => 'Net 60']" />
                    </div>
                    <div>
                        <label class="be-label">Select PO</label>
                        <x-erp.select wire:model="purchase_order_id" :options="$poOptions" />
                        @error('purchase_order_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2 lg:col-span-4">
                        <label class="be-label">Memo</label>
                        <x-erp.input wire:model="memo" />
                    </div>
                </div>
            </div>

            <div class="be-doc-tabs">
                <button type="button" class="{{ $lineTab === 'expenses' ? 'is-active' : '' }}" wire:click="$set('lineTab', 'expenses')">
                    Expenses (${{ number_format((float) $this->expensesTotal(), 2) }})
                </button>
                <button type="button" class="{{ $lineTab === 'items' ? 'is-active' : '' }}" wire:click="$set('lineTab', 'items')">
                    Items (${{ number_format((float) $this->linesSubtotal(), 2) }})
                </button>
            </div>

            @error('lines') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

            @if ($lineTab === 'expenses')
                <div class="mt-2 flex justify-end gap-2">
                    <x-erp.button type="button" wire:click="addExpenseLine">Add Expense Line</x-erp.button>
                </div>
                <table class="be-table mt-2">
                    <thead>
                        <tr>
                            <th style="width:20%">Account</th>
                            <th>Description</th>
                            <th class="text-right" style="width:16%">Amount</th>
                            <th style="width:6%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($expenseLines as $index => $line)
                            <tr wire:key="exp-{{ $index }}">
                                <td>
                                    <x-erp.input wire:model="expenseLines.{{ $index }}.account" placeholder="6000" class="font-mono" />
                                </td>
                                <td>
                                    <x-erp.input wire:model="expenseLines.{{ $index }}.description" />
                                </td>
                                <td>
                                    <x-erp.input type="number" step="0.01" min="0" class="text-right" wire:model.live="expenseLines.{{ $index }}.amount" />
                                </td>
                                <td class="text-right">
                                    <x-erp.button type="button" wire:click="removeExpenseLine({{ $index }})">×</x-erp.button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="mt-2 flex flex-wrap items-end gap-2">
                    <div class="flex-1">
                        <label class="be-label">Scan barcode / Item code</label>
                        <x-erp.input
                            wire:model="scanCode"
                            wire:keydown.enter.prevent="scanItem"
                            placeholder="Scan UPC / barcode / SKU then Enter"
                            class="font-mono"
                        />
                    </div>
                    <x-erp.button type="button" variant="primary" wire:click="scanItem">Add Scan</x-erp.button>
                    <x-erp.button type="button" wire:click="addLine">Add Line</x-erp.button>
                    <x-erp.button type="button" wire:click="selectPurchaseOrder">Receive All from PO</x-erp.button>
                </div>

                <table class="be-table mt-2">
                    <thead>
                        <tr>
                            <th style="width:16%">Item</th>
                            <th style="width:22%">Item Code</th>
                            <th>Description</th>
                            <th class="text-right" style="width:10%">Qty</th>
                            <th class="text-right" style="width:12%">Cost</th>
                            <th class="text-right" style="width:12%">Amount</th>
                            <th style="width:6%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lines as $index => $line)
                            <tr wire:key="line-{{ $index }}" class="{{ $index % 2 ? 'be-row-alt' : '' }}">
                                <td>
                                    <x-erp.select wire:model.live="lines.{{ $index }}.item_id" :options="['' => 'Select…'] + $itemOptions" />
                                </td>
                                <td>
                                    <x-erp.input wire:model.blur="lines.{{ $index }}.item_code" placeholder="SKU/barcode" class="font-mono" />
                                </td>
                                <td>
                                    <x-erp.input wire:model="lines.{{ $index }}.description" />
                                </td>
                                <td>
                                    <x-erp.input type="number" step="0.0001" min="0" class="text-right" wire:model.live="lines.{{ $index }}.quantity" />
                                </td>
                                <td>
                                    <x-erp.input type="number" step="0.01" min="0" class="text-right" wire:model.live="lines.{{ $index }}.rate" />
                                </td>
                                <td class="num">{{ number_format((float) ($line['amount'] ?? 0), 2) }}</td>
                                <td class="text-right">
                                    <x-erp.button type="button" wire:click="removeLine({{ $index }})">×</x-erp.button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="mt-4 flex justify-end gap-2">
                <a href="{{ route('vendor-bills.index') }}" class="be-btn">Clear</a>
                <x-erp.button type="button" wire:click="save">Save & Close</x-erp.button>
                <x-erp.button type="button" variant="primary" wire:click="save">Save & New</x-erp.button>
            </div>
        </div>
    </div>
</div>
