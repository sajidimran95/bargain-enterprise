<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Quote List" new-route="quotes.create" new-label="New Quote" :title="$quotes->total().' quotes'" />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Quote # / customer…" />
            <div class="be-field mb-2">
                <label class="be-field__label">Status</label>
                <x-erp.select
                    wire:model.live="status"
                    :options="[
                        'all' => 'All statuses',
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'converted' => 'Converted',
                    ]"
                />
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($quotes as $quote)
                        <tr
                            wire:key="qt-{{ $quote->id }}"
                            wire:click="selectLine({{ $quote->id }})"
                            class="{{ $selectedLineId === $quote->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $quote->quote_date?->format('m/d/Y') }}</td>
                            <td>{{ $quote->number }}</td>
                            <td>{{ $quote->customer?->display_name }}</td>
                            <td><span class="be-badge">{{ $quote->status }}</span></td>
                            <td class="num">{{ $quote->lines_count }}</td>
                            <td class="num">{{ number_format((float) $quote->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No quotes" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$quotes" />
        </div>
    </div>
</div>
