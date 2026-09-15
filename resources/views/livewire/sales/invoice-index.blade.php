<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar
        heading="Invoice List"
        new-route="invoices.create"
        new-label="Create Invoices"
        :title="'Open AR: '.number_format((float) $openBalance, 2).' · '.$invoices->total().' invoices'"
    />

    <div class="be-panel m-0 rounded-none border-0 border-t" style="border-color: var(--be-border);">
        <div class="be-panel__body">
            <div class="mb-2 flex flex-wrap items-end gap-2">
                <div class="be-field">
                    <label class="be-field__label">Look for</label>
                    <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Invoice # / customer…" class="w-56" />
                </div>
                <div class="be-field">
                    <label class="be-field__label">Status</label>
                    <x-erp.select
                        wire:model.live="status"
                        :options="[
                            'all' => 'All invoices',
                            'open' => 'Open',
                            'partial' => 'Partial',
                            'paid' => 'Paid',
                            'draft' => 'Draft',
                        ]"
                    />
                </div>
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Num</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Balance Due</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr
                            wire:key="inv-{{ $invoice->id }}"
                            wire:click="selectLine({{ $invoice->id }})"
                            class="{{ $selectedLineId === $invoice->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $invoice->invoice_date?->format('m/d/Y') }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer?->display_name }}</td>
                            <td><span class="be-badge">{{ $invoice->status }}</span></td>
                            <td class="num">{{ number_format((float) $invoice->total, 2) }}</td>
                            <td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td>
                            <td class="whitespace-nowrap">
                                <a href="{{ route('invoices.pdf', $invoice) }}" class="be-link-btn" target="_blank" @click.stop>PDF</a>
                                <button type="button" class="be-link-btn" wire:click="openInvoiceEmail({{ $invoice->id }})" @click.stop>Email</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No invoices found.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">{{ $invoices->links() }}</div>
        </div>
    </div>

    <x-erp.email-modal :show="$showEmailModal" wire-send="sendInvoiceEmail" />
</div>
