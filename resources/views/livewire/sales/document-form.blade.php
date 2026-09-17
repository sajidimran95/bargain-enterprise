<div class="be-page be-invoice-page" x-data @be-focus-scan.window="$refs.scanInput?.focus()">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" variant="primary" wire:click="save">Save</x-erp.button>
        <x-erp.workspace-link :route="$cancelRoute" class="be-btn">Cancel</x-erp.workspace-link>
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <span class="be-doc-toolbar__title">{{ $pageTitle }}</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">{{ $partyLabel }}</label>
                        <select wire:model.live="{{ $partyField }}" class="be-input be-input--combo be-input--customer">
                            <option value="">Select {{ strtolower($partyLabel) }}…</option>
                            @foreach ($partyOptions as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error($partyField) <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="be-field be-field--template">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Template</label>
                        <input type="text" class="be-input be-input--combo" value="Intuit {{ $pageTitle }}" readonly>
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">{{ $pageTitle }}</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">{{ $dateLabel }}</label>
                            <x-erp.input type="date" wire:model="{{ $dateField }}" class="be-input--combo" />
                            @error($dateField) <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">{{ $numberLabel }}</label>
                            <x-erp.input wire:model="{{ $numberField }}" class="be-input--combo" />
                            @error($numberField) <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        @if ($showPaymentMethod ?? false)
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Payment Method</label>
                                <x-erp.select
                                    wire:model="payment_method"
                                    class="be-input--combo"
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
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Due Date</label>
                                <x-erp.input type="date" wire:model="due_date" class="be-input--combo" />
                            </div>
                        @endif
                        @if ($showExpiry ?? false)
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Expiry Date</label>
                                <x-erp.input type="date" wire:model="expiry_date" class="be-input--combo" />
                            </div>
                        @endif
                        @if ($showExpected ?? false)
                            <div class="be-field be-field--inline">
                                <label class="be-field__label be-field__label--caps">Expected Date</label>
                                <x-erp.input type="date" wire:model="expected_date" class="be-input--combo" />
                            </div>
                        @endif
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input wire:model="memo" class="be-input--combo" />
                        </div>
                    </div>
                </div>

                <x-erp.item-search-bar :show-add-line="true" />
                @error('lines') <p class="be-invoice-error">{{ $message }}</p> @enderror

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid">
                        <thead>
                            <tr>
                                <th style="width:14%">ITEM CODE</th>
                                <th style="width:8%" class="text-right">QTY</th>
                                <th>DESCRIPTION</th>
                                <th style="width:22%">ITEM</th>
                                <th class="text-right" style="width:12%">{{ strtoupper($rateLabel ?? 'RATE') }}</th>
                                <th class="text-right" style="width:12%">AMOUNT</th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lines as $index => $line)
                                <tr wire:key="line-{{ $index }}">
                                    <td>
                                        <x-erp.item-code-input
                                            wire:model.blur="lines.{{ $index }}.item_code"
                                            placeholder="Code…"
                                        />
                                    </td>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="be-invoice-footer">
                    <div class="be-invoice-totals">
                        <div class="be-invoice-totals__row">
                            <span>Subtotal</span>
                            <strong>{{ number_format((float) collect($lines)->sum(fn ($l) => (float) ($l['amount'] ?? 0)), 2) }}</strong>
                        </div>
                        <div class="be-invoice-totals__row be-invoice-totals__row--total">
                            <span>Total</span>
                            <strong>{{ number_format((float) collect($lines)->sum(fn ($l) => (float) ($l['amount'] ?? 0)), 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
