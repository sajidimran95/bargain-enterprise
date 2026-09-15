<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Credit Memo List" new-route="credit-memos.create" new-label="New Credit Memo" :title="$memos->total().' credit memos'" />

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Credit memo # / customer…" />

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Total</th>
                        <th class="text-right">Remaining</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($memos as $memo)
                        <tr
                            wire:key="cm-{{ $memo->id }}"
                            wire:click="selectLine({{ $memo->id }})"
                            class="{{ $selectedLineId === $memo->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $memo->credit_date?->format('m/d/Y') }}</td>
                            <td>{{ $memo->credit_number }}</td>
                            <td>{{ $memo->customer?->display_name }}</td>
                            <td><span class="be-badge">{{ $memo->status }}</span></td>
                            <td class="num">{{ $memo->lines_count }}</td>
                            <td class="num">{{ number_format((float) $memo->total, 2) }}</td>
                            <td class="num">{{ number_format((float) $memo->remaining_credit, 2) }}</td>
                            <td class="whitespace-nowrap">
                                <a href="{{ route('credit-memos.pdf', $memo) }}" class="be-link-btn" target="_blank" @click.stop>PDF</a>
                                @if ($memo->hasRemainingCredit())
                                    <a href="{{ route('credit-memos.apply', $memo) }}" class="be-link-btn" @click.stop>Apply / Refund</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><x-erp.empty-state title="No credit memos" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$memos" />
        </div>
    </div>
</div>
