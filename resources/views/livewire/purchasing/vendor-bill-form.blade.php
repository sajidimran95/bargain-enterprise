<div class="be-page be-invoice-page be-bill-page" x-data @be-focus-scan.window="$refs.scanInput?.focus()">
    <x-erp.document-ribbon
        find-route-name="vendor-bills.index"
        new-route-name="vendor-bills.create"
        :active-tab="$ribbonTab"
        :is-pending="$is_pending"
        :show-apply-credits="false"
        :show-receive-payments="false"
        :show-refund-credit="false"
        :show-add-time-costs="false"
        :attachment-count="count($pendingAttachments)"
        :formatting-font-size="$formattingFontSize"
        :formatting-bold="$formattingBold"
    >
        <x-slot:extra>
            <button type="button" class="be-ribbon__btn" wire:click="selectPurchaseOrder" title="Select PO">
                <span class="be-ribbon__icon">📋</span><span>Select PO</span>
            </button>
            <button type="button" class="be-ribbon__btn" wire:click="clearSplits" title="Clear Splits">
                <span class="be-ribbon__icon">⌫</span><span>Clear Splits</span>
            </button>
            <button type="button" class="be-ribbon__btn" wire:click="recalculate" title="Recalculate">
                <span class="be-ribbon__icon">∑</span><span>Recalculate</span>
            </button>
            <x-erp.workspace-link route="vendor-payments.create" class="be-ribbon__btn" title="Pay Bill">
                <span class="be-ribbon__icon">💵</span><span>Pay Bill</span>
            </x-erp.workspace-link>
        </x-slot:extra>
    </x-erp.document-ribbon>

    @include('livewire.sales.partials.document-ribbon-modals')

    @if ($is_pending)
        <div class="be-invoice-pending">Pending — will not post AP until cleared.</div>
    @endif

    <div class="be-invoice-layout {{ $inspectorOpen ? '' : 'be-invoice-layout--inspector-collapsed' }}">
        <div class="be-invoice-main">
            <div class="be-invoice-doc be-bill-doc">
                <div class="be-bill-typebar">
                    <div class="be-bill-typebar__radios">
                        @if ($isRtv)
                            <span class="be-bill-radio be-bill-radio--active">Return to Vendor (RTV)</span>
                        @else
                            <label class="be-bill-radio">
                                <input type="radio" wire:model.live="docType" value="bill"> Bill
                            </label>
                            <label class="be-bill-radio">
                                <input type="radio" wire:model.live="docType" value="credit"> Credit
                            </label>
                        @endif
                    </div>
                    @unless ($isRtv)
                        <label class="be-bill-received">
                            <input type="checkbox" wire:model.live="bill_received"> Bill Received
                        </label>
                    @endunless
                </div>

                <div class="be-bill-header-grid">
                    <div class="be-bill-header-grid__left">
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Vendor</label>
                            <select wire:model.live="vendor_id" class="be-input be-input--combo be-input--customer">
                                <option value="">Select vendor…</option>
                                @foreach ($vendors as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('vendor_id') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Address</label>
                            <textarea class="be-input be-bill-address" rows="4" wire:model="address"></textarea>
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Terms</label>
                            <x-erp.select
                                wire:model="terms"
                                class="be-input--combo"
                                :options="[
                                    'Due on receipt' => 'Due on receipt',
                                    'Net 15' => 'Net 15',
                                    'Net 30' => 'Net 30',
                                    'Net 60' => 'Net 60',
                                ]"
                            />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input
                                wire:model="memo"
                                class="be-input--combo {{ $formattingBold ? 'font-bold' : '' }}"
                                style="font-size: {{ $formattingFontSize }}px"
                            />
                        </div>
                    </div>

                    <div class="be-bill-header-grid__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            <x-erp.input type="date" wire:model="bill_date" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Ref. No.</label>
                            <x-erp.input wire:model="ref_no" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline be-field--amount-due">
                            <label class="be-field__label be-field__label--caps">Amount Due</label>
                            <x-erp.input class="be-input--combo be-input--amount-due num font-semibold" value="{{ number_format((float) $amountDue, 2, '.', '') }}" readonly />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Bill Due</label>
                            <x-erp.input type="date" wire:model="due_date" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">{{ $isRtv ? 'RTV #' : ($docType === 'credit' ? 'Credit #' : 'Bill #') }}</label>
                            <x-erp.input wire:model="bill_number" class="be-input--combo" />
                            @error('bill_number') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">{{ $isRtv || $docType === 'credit' ? 'Received PO' : 'Select PO' }}</label>
                            <x-erp.select wire:model.live="purchase_order_id" class="be-input--combo" :options="$poOptions" />
                            @error('purchase_order_id') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="be-doc-tabs be-bill-tabs">
                    @unless ($isRtv)
                        <button type="button" class="{{ $lineTab === 'expenses' ? 'is-active' : '' }}" wire:click="$set('lineTab', 'expenses')">
                            Expenses (${{ number_format((float) $this->expensesTotal(), 2) }})
                        </button>
                    @endunless
                    <button type="button" class="{{ $lineTab === 'items' || $isRtv ? 'is-active' : '' }}" wire:click="$set('lineTab', 'items')">
                        {{ $isRtv ? 'Return Items' : 'Items' }} (${{ number_format((float) $this->linesSubtotal(), 2) }})
                    </button>
                </div>

                @error('lines') <p class="be-invoice-error">{{ $message }}</p> @enderror

                @if ($lineTab === 'items')
                    <x-erp.item-search-bar />
                @endif

                <div class="be-invoice-grid-wrap">
                    @if ($lineTab === 'expenses')
                        <table class="be-table be-invoice-grid">
                            <thead>
                                <tr>
                                    <th style="width:16%">ACCOUNT</th>
                                    <th>DESCRIPTION</th>
                                    <th style="width:12%" class="text-right">AMOUNT</th>
                                    <th style="width:16%">CUSTOMER:JOB</th>
                                    <th style="width:7%" class="text-center">BILLABLE</th>
                                    <th style="width:10%">CLASS</th>
                                    <th style="width:3%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($expenseLines as $index => $line)
                                    <tr wire:key="exp-{{ $index }}" class="{{ $index % 2 ? 'be-row-alt' : '' }}">
                                        <td>
                                            <x-erp.input wire:model="expenseLines.{{ $index }}.account" class="be-input--bare font-mono" />
                                        </td>
                                        <td>
                                            <x-erp.input wire:model="expenseLines.{{ $index }}.description" class="be-input--bare" />
                                        </td>
                                        <td>
                                            <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--bare" wire:model.live="expenseLines.{{ $index }}.amount" />
                                        </td>
                                        <td>
                                            <x-erp.input wire:model="expenseLines.{{ $index }}.customer_job" class="be-input--bare" />
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" wire:model="expenseLines.{{ $index }}.billable" class="be-invoice-tax">
                                        </td>
                                        <td>
                                            <x-erp.input wire:model="expenseLines.{{ $index }}.class" class="be-input--bare" />
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="be-link-btn" wire:click="removeExpenseLine({{ $index }})">×</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <table class="be-table be-invoice-grid">
                            <thead>
                                <tr>
                                    <th style="width:12%">ITEM CODE</th>
                                    <th style="width:14%">ITEM</th>
                                    <th>DESCRIPTION</th>
                                    <th style="width:8%" class="text-right">QTY</th>
                                    <th style="width:10%" class="text-right">COST</th>
                                    <th style="width:10%" class="text-right">AMOUNT</th>
                                    <th style="width:14%">CUSTOMER:JOB</th>
                                    <th style="width:7%" class="text-center">BILLABLE</th>
                                    <th style="width:9%">CLASS</th>
                                    <th style="width:3%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lines as $index => $line)
                                    <tr wire:key="line-{{ $index }}" class="{{ $index % 2 ? 'be-row-alt' : '' }}">
                                        <td>
                                            <x-erp.item-code-input
                                                wire:model.blur="lines.{{ $index }}.item_code"
                                                placeholder="A–Z code…"
                                            />
                                        </td>
                                        <td>
                                            <x-erp.select
                                                wire:model.live="lines.{{ $index }}.item_id"
                                                class="be-input--bare"
                                                :options="['' => ''] + $itemOptions"
                                            />
                                        </td>
                                        <td>
                                            <x-erp.input wire:model="lines.{{ $index }}.description" class="be-input--bare" />
                                        </td>
                                        <td>
                                            <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--bare" wire:model.live="lines.{{ $index }}.quantity" />
                                        </td>
                                        <td>
                                            <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--bare" wire:model.live="lines.{{ $index }}.rate" />
                                        </td>
                                        <td class="num be-invoice-amount">{{ number_format((float) ($line['amount'] ?? 0), 2) }}</td>
                                        <td>
                                            <x-erp.input wire:model="lines.{{ $index }}.customer_job" class="be-input--bare" />
                                        </td>
                                        <td class="text-center">
                                            <input type="checkbox" wire:model="lines.{{ $index }}.billable" class="be-invoice-tax">
                                        </td>
                                        <td>
                                            <x-erp.input wire:model="lines.{{ $index }}.class" class="be-input--bare" />
                                        </td>
                                        <td class="text-center">
                                            @if (filled($line['item_id'] ?? null))
                                                <button type="button" class="be-link-btn" wire:click="removeLine({{ $index }})">×</button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="be-bill-grid-footer">
                    <div class="be-bill-grid-footer__left">
                        @if ($isRtv)
                            <button type="button" class="be-btn" wire:click="selectPurchaseOrder" @disabled($purchase_order_id === '')>Reload Received Items</button>
                            <button type="button" class="be-btn" wire:click="clearSplits">Clear Lines</button>
                        @else
                            <button type="button" class="be-btn" wire:click="receiveAll" @disabled($purchase_order_id === '')>Receive All</button>
                            <button type="button" class="be-btn" wire:click="selectPurchaseOrder" @disabled($purchase_order_id === '')>Show PO</button>
                        @endif
                        @if ($lineTab === 'expenses' && ! $isRtv)
                            <button type="button" class="be-btn" wire:click="addExpenseLine">Add Expense Line</button>
                        @else
                            <button type="button" class="be-btn" wire:click="addLine">Add Line</button>
                        @endif
                    </div>
                    <div class="be-invoice-actions be-bill-actions">
                        <button type="button" class="be-btn" wire:click="saveAndClose">Save &amp; Close</button>
                        <button type="button" class="be-btn be-btn--primary" wire:click="saveAndNew">Save &amp; New</button>
                        <button type="button" class="be-btn" wire:click="clearForm">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" class="be-invoice-inspector-toggle" wire:click="toggleInspector" title="{{ $inspectorOpen ? 'Hide panel' : 'Show panel' }}">
            {{ $inspectorOpen ? '›' : '‹' }}
        </button>

        @if ($inspectorOpen)
            <aside class="be-invoice-inspector">
                @if ($selectedVendor)
                    <div class="be-inspector-head">
                        <div class="be-inspector-head__id">{{ $selectedVendor->vendor_number ?: '—' }}</div>
                        <div class="be-inspector-head__name">{{ $selectedVendor->display_name }}</div>
                    </div>
                @endif

                <div class="be-inspector-tabs">
                    <button type="button" class="{{ $inspectorTab === 'name' ? 'is-active' : '' }}" wire:click="$set('inspectorTab', 'name')">Name</button>
                    <button type="button" class="{{ $inspectorTab === 'transaction' ? 'is-active' : '' }}" wire:click="$set('inspectorTab', 'transaction')">Transaction</button>
                </div>

                @if ($inspectorTab === 'transaction')
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Transaction</h3></div>
                        <dl class="be-inspector-summary">
                            <div><dt>Type</dt><dd>{{ $docType === 'credit' ? 'Credit' : 'Bill' }}</dd></div>
                            <div><dt>Status</dt><dd>{{ $is_pending ? 'Pending' : 'Open' }}</dd></div>
                            <div><dt>Bill received</dt><dd>{{ $bill_received ? 'Yes' : 'No' }}</dd></div>
                            <div><dt>Amount due</dt><dd>{{ number_format((float) $amountDue, 2) }}</dd></div>
                        </dl>
                    </div>
                @elseif ($selectedVendor)
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Summary</h3></div>
                        <dl class="be-inspector-summary">
                            <div>
                                <dt>Open balance</dt>
                                <dd class="be-inspector-balance {{ (float) $selectedVendor->balance > 0 ? 'is-due' : '' }}">
                                    {{ number_format((float) $selectedVendor->balance, 2) }}
                                </dd>
                            </div>
                            <div>
                                <dt>Terms</dt>
                                <dd>{{ $selectedVendor->terms ?: $terms }}</dd>
                            </div>
                            <div>
                                <dt>Account #</dt>
                                <dd>{{ $selectedVendor->account_number ?: '—' }}</dd></div>
                        </dl>
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Recent Transactions</h3></div>
                        @forelse ($recentBills as $bill)
                            <div class="be-inspector-txn">
                                <span>{{ $bill->bill_date?->format('m/d/y') }} Bill</span>
                                <span class="be-inspector-txn__amt">{{ number_format((float) $bill->total, 2) }}</span>
                            </div>
                        @empty
                            <p class="be-inspector-empty">No recent bills.</p>
                        @endforelse
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Notes</h3></div>
                        <p class="be-inspector-notes">{{ $selectedVendor->notes ?: '' }}</p>
                    </div>
                @else
                    <div class="be-inspector-section">
                        <p class="be-inspector-empty">Select a vendor to see balance, recent transactions, and notes.</p>
                    </div>
                @endif
            </aside>
        @endif
    </div>
</div>
