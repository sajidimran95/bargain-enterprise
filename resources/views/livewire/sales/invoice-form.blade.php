<div class="be-page be-invoice-page" x-data @be-focus-scan.window="$refs.scanInput?.focus()">
    <x-erp.document-ribbon
        find-route-name="invoices.index"
        new-route-name="invoices.create"
        :active-tab="$ribbonTab"
        :is-pending="$is_pending"
        :show-apply-credits="true"
        :show-receive-payments="true"
        :show-refund-credit="true"
        apply-credits-route-name="credit-memos.index"
        receive-payments-route-name="payments.create"
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
                                'Intuit Product Invoice' => 'Intuit Product Invoice',
                                'Copy of Intuit Product Invoice' => 'Copy of Intuit Product Invoice',
                            ]"
                        />
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Invoice</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Date</label>
                            <x-erp.input type="date" wire:model="invoice_date" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Invoice #</label>
                            <x-erp.input wire:model="invoice_number" class="be-input--combo" />
                        </div>
                        @error('invoice_number') <span class="be-field__error">{{ $message }}</span> @enderror
                        @if ($savedInvoice)
                            <div class="be-invoice-audit text-[11px] text-gray-700 leading-snug mt-1">
                                <div>
                                    <strong>Created:</strong>
                                    {{ $savedInvoice->created_at?->format('m/d/Y g:i A') ?? '—' }}
                                    by {{ $savedInvoice->createdBy?->name ?? 'System' }}
                                </div>
                                <div>
                                    <strong>Last edit:</strong>
                                    {{ $savedInvoice->updated_at?->format('m/d/Y g:i A') ?? '—' }}
                                    by {{ $savedInvoice->updatedBy?->name ?? $savedInvoice->createdBy?->name ?? 'System' }}
                                </div>
                            </div>
                        @endif
                        <div class="be-field">
                            <label class="be-field__label be-field__label--caps">Bill To</label>
                            <textarea class="be-input be-invoice-billto" rows="5" readonly>{{ $selectedCustomer?->formattedBillingAddress() ?: '' }}</textarea>
                        </div>
                    </div>
                </div>

                <x-erp.item-search-bar />
                @error('lines') <p class="be-invoice-error">{{ $message }}</p> @enderror

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid">
                        <thead>
                            <tr>
                                <th style="width:13%">ITEM CODE</th>
                                <th style="width:7%" class="text-right">QTY</th>
                                <th>DESCRIPTION</th>
                                <th style="width:11%" class="text-right">PRICE EACH</th>
                                <th style="width:10%">CLASS</th>
                                <th style="width:11%" class="text-right">AMOUNT</th>
                                <th style="width:5%" class="text-center">TAX</th>
                                <th style="width:3%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lines as $index => $line)
                                <tr wire:key="inv-line-{{ $index }}" class="{{ $index % 2 ? 'be-row-alt' : '' }}">
                                    <td>
                                        <x-erp.item-code-input
                                            wire:model.blur="lines.{{ $index }}.item_code"
                                            placeholder="Code…"
                                        />
                                    </td>
                                    <td>
                                        <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--bare" wire:model.live="lines.{{ $index }}.quantity" />
                                    </td>
                                    <td>
                                        <x-erp.input wire:model="lines.{{ $index }}.description" class="be-input--bare" />
                                    </td>
                                    <td>
                                        <x-erp.input type="number" step="0.01" min="0" class="text-right be-input--bare" wire:model.live="lines.{{ $index }}.rate" />
                                    </td>
                                    <td>
                                        <x-erp.input wire:model="lines.{{ $index }}.class" class="be-input--bare" />
                                    </td>
                                    <td class="num be-invoice-amount">{{ number_format((float) ($line['amount'] ?? 0), 2) }}</td>
                                    <td class="text-center">
                                        <input type="checkbox" wire:model.live="lines.{{ $index }}.taxable" class="be-invoice-tax">
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
                        @if ($savedInvoice)
                            @php
                                $paidSoFar = (float) $savedInvoice->amount_paid;
                                $editBalance = max(0, (float) $total - $paidSoFar);
                                $editOverpay = max(0, $paidSoFar - (float) $total);
                            @endphp
                            <div class="be-invoice-totals__row">
                                <span>Amount Paid</span>
                                <strong>{{ number_format($paidSoFar, 2) }}</strong>
                            </div>
                            <div class="be-invoice-totals__row be-invoice-totals__row--balance">
                                <span>Balance Due</span>
                                <strong>{{ number_format($editBalance, 2) }}</strong>
                            </div>
                            @if ($editOverpay > 0)
                                <div class="be-invoice-totals__row text-[11px]" style="color:#8a4b00;">
                                    <span>Overpayment → credit</span>
                                    <strong>{{ number_format($editOverpay, 2) }}</strong>
                                </div>
                            @elseif ($editBalance > 0 && $paidSoFar > 0)
                                <div class="be-invoice-totals__row text-[11px] text-gray-600">
                                    <span>Still to collect</span>
                                    <strong>{{ number_format($editBalance, 2) }}</strong>
                                </div>
                            @endif
                        @else
                            <div class="be-invoice-totals__row">
                                <span>Payments Applied</span>
                                <strong>{{ $receive_payment_now && ! $is_pending ? number_format((float) ($payment_amount !== '' ? $payment_amount : $total), 2) : '0.00' }}</strong>
                            </div>
                            <div class="be-invoice-totals__row be-invoice-totals__row--balance">
                                <span>Balance Due</span>
                                <strong>
                                    @php
                                        $appliedPreview = ($receive_payment_now && ! $is_pending)
                                            ? (float) ($payment_amount !== '' ? $payment_amount : $total)
                                            : 0;
                                        $balancePreview = max(0, (float) $total - $appliedPreview);
                                    @endphp
                                    {{ number_format($balancePreview, 2) }}
                                </strong>
                            </div>
                        @endif
                    </div>
                </div>

                @unless ($savedInvoice)
                <div class="be-invoice-paynow border px-2 py-2 mb-2" style="border-color:#8aa3bc;background:#f3f7fb;">
                    <label class="inline-flex items-center gap-2 text-[12px] font-semibold">
                        <input type="checkbox" wire:model.live="receive_payment_now" @disabled($is_pending)>
                        Receive payment now (pay with this invoice)
                    </label>
                    @if ($receive_payment_now && ! $is_pending)
                        <div class="mt-2 grid gap-2 md:grid-cols-3">
                            <div class="be-field">
                                <label class="be-field__label be-field__label--caps">Method</label>
                                <x-erp.select
                                    wire:model.live="payment_method"
                                    class="be-input--combo"
                                    :options="$paymentMethodOptions"
                                />
                                @if ($paymentMethodOptions === [])
                                    <p class="mt-1 text-[11px] text-amber-700">
                                        No methods —
                                        <x-erp.workspace-link route="payment-methods.index" class="be-link-btn">Payment Method List</x-erp.workspace-link>
                                    </p>
                                @endif
                            </div>
                            <div class="be-field">
                                <label class="be-field__label be-field__label--caps">Amount</label>
                                <x-erp.input type="number" step="0.01" min="0" wire:model.live="payment_amount" class="be-input--combo text-right" />
                            </div>
                            <div class="be-field">
                                <label class="be-field__label be-field__label--caps">Reference #</label>
                                <x-erp.input wire:model="payment_reference" class="be-input--combo" placeholder="Check / Ref #" />
                            </div>
                        </div>
                        <p class="mt-1 text-[11px] text-gray-600">Leave amount blank to pay the full invoice total ({{ number_format((float) $total, 2) }}).</p>
                    @elseif ($is_pending)
                        <p class="mt-1 text-[11px] text-gray-600">Clear Pending before receiving payment.</p>
                    @endif
                </div>
                @else
                <p class="mb-2 text-[11px] text-gray-600">Editing {{ $savedInvoice->invoice_number }}. Save updates stock and paid/balance. Extra due stays open; overpayment becomes a customer credit.</p>
                @endunless

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
                    <button type="button" class="{{ $inspectorTab === 'history' ? 'is-active' : '' }}" wire:click="$set('inspectorTab', 'history')">History</button>
                </div>

                @if ($inspectorTab === 'history')
                    <div class="be-inspector-section">
                        <h3>Who / When</h3>
                        @if ($savedInvoice)
                            <dl class="be-inspector-summary text-[11px]">
                                <div>
                                    <dt>Invoice date</dt>
                                    <dd>{{ $savedInvoice->invoice_date?->format('m/d/Y') }}</dd>
                                </div>
                                <div>
                                    <dt>Created</dt>
                                    <dd>{{ $savedInvoice->created_at?->format('m/d/Y g:i A') }}<br>{{ $savedInvoice->createdBy?->name ?? 'System' }}</dd>
                                </div>
                                <div>
                                    <dt>Last edit</dt>
                                    <dd>{{ $savedInvoice->updated_at?->format('m/d/Y g:i A') }}<br>{{ $savedInvoice->updatedBy?->name ?? $savedInvoice->createdBy?->name ?? 'System' }}</dd>
                                </div>
                                <div>
                                    <dt>Status</dt>
                                    <dd>{{ $savedInvoice->status }}</dd>
                                </div>
                                <div>
                                    <dt>Paid / Balance</dt>
                                    <dd>{{ number_format((float) $savedInvoice->amount_paid, 2) }} / {{ number_format((float) $savedInvoice->balance_due, 2) }}</dd>
                                </div>
                            </dl>
                            <h3 class="mt-3">Activity</h3>
                            <div class="space-y-2 max-h-72 overflow-auto">
                                @forelse ($auditTrail as $log)
                                    <div class="border px-1.5 py-1 text-[11px]" style="border-color: var(--be-border);" wire:key="inv-audit-{{ $log->id }}">
                                        <div class="font-semibold">{{ ucfirst($log->action) }} · {{ $log->user?->name ?? 'System' }}</div>
                                        <div class="text-gray-500">{{ $log->created_at?->format('m/d/Y g:i A') }}</div>
                                        @if (is_array($log->new_values) && $log->new_values !== [])
                                            <div class="mt-0.5 text-gray-700">
                                                @foreach (collect($log->new_values)->only(['invoice_number','status','total','amount_paid','balance_due','customer_id','method','allocated'])->filter() as $key => $value)
                                                    <div>{{ str_replace('_', ' ', $key) }}: {{ is_scalar($value) ? $value : json_encode($value) }}</div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="be-inspector-empty">No activity logged yet. Save the invoice to start history.</p>
                                @endforelse
                            </div>
                        @else
                            <p class="be-inspector-empty">Save or open an invoice (Prev/Next) to see who created/edited it and full activity.</p>
                        @endif
                    </div>
                @elseif ($inspectorTab === 'transaction')
                    <div class="be-inspector-section">
                        <h3>Transaction</h3>
                        <dl>
                            <div><dt>Status</dt><dd>{{ $is_pending ? 'Pending' : 'Open' }}</dd></div>
                            <div><dt>Template</dt><dd>{{ $template }}</dd></div>
                            <div><dt>Print later</dt><dd>{{ $print_later ? 'Yes' : 'No' }}</dd></div>
                            <div><dt>Email later</dt><dd>{{ $email_later ? 'Yes' : 'No' }}</dd></div>
                            @if ($savedInvoice)
                                <div><dt>Created</dt><dd>{{ $savedInvoice->created_at?->format('m/d/Y g:i A') }} · {{ $savedInvoice->createdBy?->name ?? 'System' }}</dd></div>
                                <div><dt>Last edit</dt><dd>{{ $savedInvoice->updated_at?->format('m/d/Y g:i A') }} · {{ $savedInvoice->updatedBy?->name ?? $savedInvoice->createdBy?->name ?? 'System' }}</dd></div>
                            @endif
                        </dl>
                    </div>
                @elseif ($selectedCustomer)
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title">
                            <h3>Summary</h3>
                        </div>
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
                        <div class="be-inspector-section__title">
                            <h3>Customer Payment</h3>
                        </div>
                        <p class="be-inspector-note">
                            {{ $selectedCustomer->online_payment_eligible
                                ? 'This customer can pay invoices online.'
                                : "Your customer can't pay this invoice online" }}
                        </p>
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title">
                            <h3>Recent Transactions</h3>
                        </div>
                        @forelse ($recentInvoices as $inv)
                            <div class="be-inspector-txn">
                                <span>{{ $inv->invoice_date?->format('m/d/y') }} Invoice</span>
                                <span class="be-inspector-txn__amt">{{ number_format((float) $inv->total, 2) }}</span>
                            </div>
                        @empty
                            <p class="be-inspector-empty">No recent invoices.</p>
                        @endforelse
                    </div>
                    <div class="be-inspector-section">
                        <div class="be-inspector-section__title">
                            <h3>Notes</h3>
                        </div>
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
