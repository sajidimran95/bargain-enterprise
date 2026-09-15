<x-erp.report-shell
    title="Sales by Item"
    :subtitle="$subtitle"
    :show-basis="true"
    :basis="$basis"
    :hide-header="$hideHeader"
    :show-extra-filters="$showExtraFilters"
    :sort-by="$sortBy"
    :date-preset-options="$datePresetOptions"
    :sort-by-options="$sortByOptions"
    :show-email-modal="$showEmailModal"
>
    <x-slot:excel>
        <x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button>
    </x-slot:excel>

    <table class="be-report-table be-table be-table--line-select">
        <thead>
            <tr>
                <th>Date</th>
                <th>Num</th>
                <th>Customer</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null }">
            @forelse ($groups as $group)
                <tr class="be-report-table__group">
                    <td colspan="6">{{ $group['item_label'] }}</td>
                </tr>
                @foreach ($group['lines'] as $line)
                    <tr
                        @click="selectedLine = 'line-{{ $line->id }}'"
                        :class="selectedLine === 'line-{{ $line->id }}' ? 'is-selected' : ''"
                    >
                        <td>{{ $line->invoice?->invoice_date?->format('m/d/Y') }}</td>
                        <td>{{ $line->invoice?->invoice_number }}</td>
                        <td>{{ $line->invoice?->customer?->display_name }}</td>
                        <td class="num">{{ number_format((float) $line->quantity, 2) }}</td>
                        <td class="num">{{ number_format((float) $line->amount, 2) }}</td>
                        <td class="num">{{ number_format((float) ($line->invoice?->balance_due ?? 0), 2) }}</td>
                    </tr>
                @endforeach
                <tr class="be-report-table__subtotal">
                    <td colspan="3" class="text-right">Total {{ $group['item_label'] }}</td>
                    <td class="num">{{ number_format((float) $group['qty'], 2) }}</td>
                    <td class="num">{{ number_format((float) $group['amount'], 2) }}</td>
                    <td></td>
                </tr>
            @empty
                <tr><td colspan="6">No sales in this date range.</td></tr>
            @endforelse
            @if ($groups->isNotEmpty())
                <tr class="be-report-table__total">
                    <td colspan="3" class="text-right">TOTAL</td>
                    <td class="num">{{ number_format((float) $grandQty, 2) }}</td>
                    <td class="num">{{ number_format((float) $grandAmount, 2) }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
