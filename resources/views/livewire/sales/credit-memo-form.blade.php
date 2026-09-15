<div class="be-page be-invoice-page" x-data @be-focus-scan.window="$refs.scanInput?.focus()">
    <x-erp.document-ribbon
        find-route-name="credit-memos.index"
        new-route-name="credit-memos.create"
        :active-tab="$ribbonTab"
        :is-pending="$is_pending"
        :show-apply-credits="true"
        :show-receive-payments="false"
        :show-refund-credit="true"
        apply-credits-route-name="credit-memos.index"
        refund-credit-route-name="credit-memos.create"
        :attachment-count="count($pendingAttachments)"
        :formatting-font-size="$formattingFontSize"
        :formatting-bold="$formattingBold"
    />

    @include('livewire.sales.partials.document-ribbon-modals')

    @if ($is_pending)
        <div class="be-invoice-pending">Pending — will not post inventory or AR until cleared.</div>
    @endif

    <div class="be-invoice-layout {{ $inspectorOpen ? '' : 'be-invoice-layout--inspector-collapsed' }}">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Customer:Job</label>
                        <select wire:model.live="customer_id" class="be-input be-input--combo be-input--customer">
                            <option value="">Select customer…</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->customer_number
                                        ? $customer->customer_number.' ('.$customer->display_name.')'
                                        : $customer->display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="be-field be-field--class">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Class</label>
                        <x-erp.input wire:model="class" class="be-input--combo" />
                    </div>
                    <div class="be-field be-field--template">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Template</label>
                        <x-erp.select
                            wire:model="template"
                            class="be-input--combo"
                            :options="[
                                'Custom Credit Memo' => 'Custom Credit Memo',
                                'Intuit Product Invoice' => 'Intuit Product Invoice',
                            ]"
                        />
                    </div>
                </div>

                <div class="be-invoice-midrow be-credit-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Credit Memo</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            <x-erp.input type="date" wire:model="credit_date" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Credit No.</label>
                            <x-erp.input wire:model="credit_number" class="be-input--combo" />
                        </div>
                        @error('credit_number') <span class="be-field__error">{{ $message }}</span> @enderror
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Customer</label>
                            <textarea class="be-input be-invoice-billto" rows="5" readonly>{{ $selectedCustomer?->formattedBillingAddress() ?: '' }}</textarea>
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">P.O. No.</label>
                            <x-erp.input wire:model="po_number" class="be-input--combo" />
                        </div>
                    </div>
                </div>

                <div class="be-scan-bar be-scan-bar--compact">
                    <label class="be-field__label be-field__label--caps mb-0">Item / Scan</label>
                    <input
                        x-ref="scanInput"
                        type="text"
                        class="be-input be-scan-input"
                        wire:model="scanCode"
                        wire:keydown.enter.prevent="scanItem"
                        placeholder="Barcode / SKU / UPC — Enter"
                    >
                    <button type="button" class="be-btn be-btn--primary" wire:click="scanItem">Add</button>
                </div>
                @error('lines') <p class="be-invoice-error">{{ $message }}</p> @enderror

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid">
                        <thead>
                            <tr>
                                <th style="width:22%">ITEM</th>
                                <th>DESCRIPTION</th>
                                <th style="width:5%" class="text-center">TAX</th>
                                <th style="width:9%" class="text-right">QTY</th>
                                <th style="width:11%" class="text-right">RATE</th>
                                <th style="width:12%" class="text-right">AMOUNT</th>
                                <th style="width:3%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lines as $index => $line)
                                <tr wire:key="cm-line-{{ $index }}" class="{{ $index % 2 ? 'be-row-alt' : '' }}">
                                    <td>
                                        <x-erp.select
                                            wire:model.live="lines.{{ $index }}.item_id"
                                            :options="['' => ''] + $itemOptions"
                                            class="be-input--bare"
                                        />
                                    </td>
                                    <td>
                                        <x-erp.input wire:model="lines.{{ $index }}.description" class="be-input--bare" />
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" wire:model.live="lines.{{ $index }}.taxable" class="be-invoice-tax">
                                    </td>
                                    <td>
                                        <x-erp.input type="number" step="0.0001" min="0" class="text-right be-input--bare" wire:model.live="lines.{{ $index }}.quantity" />
                                    </td>
                                    <td>
                                        <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--bare" wire:model.live="lines.{{ $index }}.rate" />
                                    </td>
                                    <td class="num be-invoice-amount">{{ number_format((float) ($line['amount'] ?? 0), 2) }}</td>
                                    <td class="text-center">
                                        @if (filled($line['item_id'] ?? null))
                                            <button type="button" class="be-link-btn" wire:click="removeLine({{ $index }})">×</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="be-invoice-footer">
                    <div class="be-invoice-footer__left">
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Customer Message</label>
                            <x-erp.input
                                wire:model="customer_message"
                                class="be-input--combo {{ $formattingBold ? 'font-bold' : '' }}"
                                style="font-size: {{ $formattingFontSize }}px"
                            />
                        </div>
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Memo</label>
                            <x-erp.input
                                wire:model="memo"
                                class="be-input--combo {{ $formattingBold ? 'font-bold' : '' }}"
                                style="font-size: {{ $formattingFontSize }}px"
                            />
                        </div>
                        <div class="be-field be-field--taxcode">
                            <label class="be-field__label be-field__label--caps">Customer Tax Code</label>
                            <x-erp.select wire:model.live="tax_code_id" class="be-input--combo" :options="['' => 'Non'] + $taxCodes" />
                        </div>
                    </div>
                    <div class="be-invoice-totals">
                        <div class="be-invoice-totals__row">
                            <span>Tax</span>
                            <span class="be-invoice-totals__tax">
                                <span>{{ number_format((float) $taxRate, 2) }}%</span>
                                <strong>{{ number_format((float) $taxTotal, 2) }}</strong>
                            </span>
                        </div>
                        <div class="be-invoice-totals__row be-invoice-totals__row--total">
                            <span>Total</span>
                            <strong>{{ number_format((float) $total, 2) }}</strong>
                        </div>
                        <div class="be-invoice-totals__row be-invoice-totals__row--balance">
                            <span>Remaining Credit</span>
                            <strong>{{ number_format((float) $total, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <div class="be-invoice-actions">
                    <button type="button" class="be-btn be-btn--primary" wire:click="saveAndClose">Save &amp; Close</button>
                    <button type="button" class="be-btn be-btn--primary" wire:click="saveAndNew">Save &amp; New</button>
                    <button type="button" class="be-btn" wire:click="clearForm">Clear</button>
                </div>
            </div>
        </div>

        <button type="button" class="be-invoice-inspector-toggle" wire:click="toggleInspector" title="{{ $inspectorOpen ? 'Hide panel' : 'Show panel' }}">
            {{ $inspectorOpen ? '›' : '‹' }}
        </button>

        @if ($inspectorOpen)
            <aside class="be-invoice-inspector">
                @if ($selectedCustomer)
                    <div class="be-inspector-head">
                        <div class="be-inspector-head__id">{{ $selectedCustomer->customer_number ?: '—' }}</div>
                        <div class="be-inspector-head__name">{{ $selectedCustomer->display_name }}</div>
                    </div>
                @endif

                <div class="be-inspector-tabs">
                    <button type="button" class="{{ $inspectorTab === 'name' ? 'is-active' : '' }}" wire:click="$set('inspectorTab', 'name')">Customer</button>
                    <button type="button" class="{{ $inspectorTab === 'transaction' ? 'is-active' : '' }}" wire:click="$set('inspectorTab', 'transaction')">Transaction</button>
                </div>

                @if ($inspectorTab === 'transaction')
                    <div class="be-inspector-section">
                        <h3>Transaction</h3>
                        <dl class="be-inspector-summary">
                            <div><dt>Status</dt><dd>{{ $is_pending ? 'Pending' : 'Open' }}</dd></div>
                            <div><dt>Template</dt><dd>{{ $template }}</dd></div>
                            <div><dt>Print later</dt><dd>{{ $print_later ? 'Yes' : 'No' }}</dd></div>
                            <div><dt>Email later</dt><dd>{{ $email_later ? 'Yes' : 'No' }}</dd></div>
                        </dl>
                    </div>
                @elseif ($selectedCustomer)
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Summary</h3></div>
                        <dl class="be-inspector-summary">
                            <div>
                                <dt>Preferred delivery method</dt>
                                <dd>None</dd>
                            </div>
                            <div>
                                <dt>Open balance</dt>
                                <dd class="be-inspector-balance {{ (float) $selectedCustomer->balance > 0 ? 'is-due' : '' }}">
                                    {{ number_format((float) $selectedCustomer->balance, 2) }}
                                </dd>
                            </div>
                            <div>
                                <dt>Active estimates</dt>
                                <dd>0</dd>
                            </div>
                            <div>
                                <dt>Sales Orders to be Invoiced</dt>
                                <dd>0</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Customer Payment</h3></div>
                        <p class="be-inspector-note">
                            {{ $selectedCustomer->online_payment_eligible
                                ? 'This customer can pay invoices online.'
                                : "Your customer can't pay this invoice online" }}
                        </p>
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Recent Transactions</h3></div>
                        @forelse ($recentCredits as $cm)
                            <div class="be-inspector-txn">
                                <span>{{ $cm->credit_date?->format('m/d/y') }} Credit</span>
                                <span class="be-inspector-txn__amt">{{ number_format((float) $cm->total, 2) }}</span>
                            </div>
                        @empty
                            <p class="be-inspector-empty">No recent credit memos.</p>
                        @endforelse
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title"><h3>Notes</h3></div>
                        <p class="be-inspector-notes">{{ $selectedCustomer->pinned_note ?: '' }}</p>
                    </div>
                @else
                    <div class="be-inspector-section">
                        <h3>Summary</h3>
                        <p class="be-inspector-empty">Select a customer to see balance, recent transactions, and notes.</p>
                    </div>
                @endif
            </aside>
        @endif
    </div>
</div>
