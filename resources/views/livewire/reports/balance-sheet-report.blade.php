<x-erp.report-shell title="Balance Sheet" :subtitle="$subtitle" :hide-header="$hideHeader" :show-extra-filters="$showExtraFilters" :sort-by="$sortBy" :date-preset-options="$datePresetOptions" :sort-by-options="$sortByOptions" :show-email-modal="$showEmailModal">
    <x-slot:excel><x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button></x-slot:excel>
    <table class="be-report-table be-table">
        <tbody>
            <tr class="be-report-table__group"><td colspan="2">ASSETS</td></tr>
            @foreach ($statement['assets'] as $row)
                <tr><td>{{ $row['name'] }}</td><td class="num">{{ number_format($row['amount'], 2) }}</td></tr>
            @endforeach
            <tr class="be-report-table__total"><td>Total Assets</td><td class="num">{{ number_format($statement['asset_total'], 2) }}</td></tr>
            <tr class="be-report-table__group"><td colspan="2">LIABILITIES</td></tr>
            @foreach ($statement['liabilities'] as $row)
                <tr><td>{{ $row['name'] }}</td><td class="num">{{ number_format($row['amount'], 2) }}</td></tr>
            @endforeach
            <tr class="be-report-table__subtotal"><td>Total Liabilities</td><td class="num">{{ number_format($statement['liability_total'], 2) }}</td></tr>
            <tr class="be-report-table__group"><td colspan="2">EQUITY</td></tr>
            @foreach ($statement['equity'] as $row)
                <tr><td>{{ $row['name'] }}</td><td class="num">{{ number_format($row['amount'], 2) }}</td></tr>
            @endforeach
            <tr class="be-report-table__total"><td>Total Liabilities & Equity</td><td class="num">{{ number_format($statement['liability_total'] + $statement['equity_total'], 2) }}</td></tr>
        </tbody>
    </table>
</x-erp.report-shell>
