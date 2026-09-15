<x-erp.report-shell title="Vendor Balance Summary" :subtitle="$subtitle" :hide-header="$hideHeader" :show-extra-filters="$showExtraFilters" :sort-by="$sortBy" :date-preset-options="$datePresetOptions" :sort-by-options="$sortByOptions" :show-email-modal="$showEmailModal">
    <x-slot:excel><x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button></x-slot:excel>
    <table class="be-report-table be-table be-table--line-select">
        <thead><tr><th>Vendor</th><th class="num">Billed</th><th class="num">Balance Due</th></tr></thead>
        <tbody>
            @forelse ($rows as $row)
                <tr><td>{{ $row['vendor'] }}</td><td class="num">{{ number_format($row['total'], 2) }}</td><td class="num">{{ number_format($row['balance'], 2) }}</td></tr>
            @empty
                <tr><td colspan="3">No vendor balances in this period.</td></tr>
            @endforelse
            @if ($rows->isNotEmpty())
                <tr class="be-report-table__total"><td class="text-right">TOTAL</td><td class="num">{{ number_format($grandTotal, 2) }}</td><td class="num">{{ number_format($grandBalance, 2) }}</td></tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
