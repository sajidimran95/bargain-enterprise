<div class="be-page">
    <x-erp.toolbar>
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <a href="{{ route($cancelRoute) }}" class="be-btn">Cancel</a>
        <span class="ml-auto text-[11px] text-gray-500">{{ $pageTitle }}</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">{{ $pageTitle }}</h1>
        </div>
        <div class="be-panel__body">
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                <div>
                    <label class="be-label">{{ $numberLabel }}</label>
                    <x-erp.input wire:model="{{ $numberField }}" />
                    @error($numberField) <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">{{ $partyLabel }} *</label>
                    <x-erp.select wire:model="{{ $partyField }}" :options="['' => 'Select…'] + $partyOptions" />
                    @error($partyField) <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">{{ $dateLabel }}</label>
                    <x-erp.input type="date" wire:model="{{ $dateField }}" />
                    @error($dateField) <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                @if ($showPaymentMethod ?? false)
                    <div>
                        <label class="be-label">Payment Method</label>
                        <x-erp.select
                            wire:model="payment_method"
                            :options="[
                                'cash' => 'Cash',
                                'check' => 'Check',
                                'credit_card' => 'Credit Card',
                                'other' => 'Other',
                            ]"
                        />
                    </div>
                @endif
                @if ($showDueDate ?? false)
                    <div>
                        <label class="be-label">Due Date</label>
                        <x-erp.input type="date" wire:model="due_date" />
                    </div>
                @endif
                @if ($showExpiry ?? false)
                    <div>
                        <label class="be-label">Expiry Date</label>
                        <x-erp.input type="date" wire:model="expiry_date" />
                    </div>
                @endif
                                @if ($showExpected ?? false)
                    <div>
                        <label class="be-label">Expected Date</label>
                        <x-erp.input type="date" wire:model="expected_date" />
                    </div>
                @endif
                <div class="md:col-span-2">
                    <label class="be-label">Memo</label>
                    <x-erp.input wire:model="memo" />
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-end gap-2">
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
            </div>
            @error('lines') <p class="text-xs text-red-600">{{ $message }}</p> @enderror

            <table class="be-table mt-2">
                <thead>
                    <tr>
                        <th style="width:16%">Item Code</th>
                        <th style="width:22%">Item</th>
                        <th>Description</th>
                        <th class="text-right" style="width:10%">Qty</th>
                        <th class="text-right" style="width:12%">{{ $rateLabel ?? 'Rate' }}</th>
                        <th class="text-right" style="width:12%">Amount</th>
                        <th style="width:6%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lines as $index => $line)
                        <tr wire:key="line-{{ $index }}">
                            <td>
                                <x-erp.input wire:model.blur="lines.{{ $index }}.item_code" placeholder="SKU/barcode" class="font-mono" />
                            </td>
                            <td>
                                <x-erp.select wire:model.live="lines.{{ $index }}.item_id" :options="['' => 'Select…'] + $itemOptions" />
                                @error("lines.$index.item_id") <p class="text-xs text-red-600">{{ $message }}</p> @enderror
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
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-right font-semibold">Subtotal</td>
                        <td class="num font-semibold">{{ number_format((float) collect($lines)->sum(fn ($l) => (float) ($l['amount'] ?? 0)), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
