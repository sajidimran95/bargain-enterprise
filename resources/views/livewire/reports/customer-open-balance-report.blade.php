<x-erp.report-shell
    title="Customer Open Balance"
    :subtitle="$subtitle"
    :show-basis="true"
    :basis="$basis"
    :hide-header="$hideHeader"
    :show-extra-filters="$showExtraFilters"
    :sort-by="$sortBy"
    :date-preset-options="$datePresetOptions"
    :sort-by-options="$sortByOptions"
    :show-email-modal="$showEmailModal"
    :show-comment-modal="$showCommentModal"
    :show-share-modal="$showShareModal"
    :show-memorize-modal="$showMemorizeModal"
    :report-comment="$reportComment"
    :share-url="$shareUrl"
    :memorize-name="$memorizeName"
    :email-subject="$emailSubject"
>
    <x-slot:excel>
        <x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button>
    </x-slot:excel>

    <table class="be-report-table be-table be-table--line-select">
        <thead>
            <tr>
                <th>Type</th>
                <th>Date</th>
                <th>Num</th>
                <th>Memo</th>
                <th>Due Date</th>
                <th class="num">Open Balance</th>
                <th class="num">Amount</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null, collapsed: {} }">
            @forelse ($groups as $group)
                @php($groupKey = 'g-'.$loop->index)
                <tr
                    class="be-report-table__group"
                    @click="collapsed['{{ $groupKey }}'] = !collapsed['{{ $groupKey }}']"
                >
                    <td colspan="7">
                        <span
                            class="be-report-table__group-toggle"
                            x-text="collapsed['{{ $groupKey }}'] ? '▶' : '▼'"
                        ></span>
                        {{ $group['customer_label'] }}
                    </td>
                </tr>
                @foreach ($group['invoices'] as $invoice)
                    <tr
                        x-show="!collapsed['{{ $groupKey }}']"
                        @click="selectedLine = 'inv-{{ $invoice->id }}'"
                        :class="selectedLine === 'inv-{{ $invoice->id }}' ? 'is-selected' : ''"
                    >
                        <td>Invoice</td>
                        <td>{{ $invoice->invoice_date?->format('m/d/Y') }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->memo }}</td>
                        <td>{{ $invoice->due_date?->format('m/d/Y') }}</td>
                        <td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td>
                        <td class="num">{{ number_format((float) $invoice->total, 2) }}</td>
                    </tr>
                @endforeach
                <tr class="be-report-table__subtotal" x-show="!collapsed['{{ $groupKey }}']">
                    <td colspan="5" class="text-right">Total {{ $group['customer_label'] }}</td>
                    <td class="num">{{ number_format((float) $group['open_balance'], 2) }}</td>
                    <td class="num">{{ number_format((float) $group['amount'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No open balances in this date range.</td></tr>
            @endforelse
            @if ($groups->isNotEmpty())
                <tr class="be-report-table__total">
                    <td colspan="5" class="text-right">TOTAL</td>
                    <td class="num">{{ number_format((float) $grandOpen, 2) }}</td>
                    <td class="num">{{ number_format((float) $grandAmount, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
