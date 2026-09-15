<x-erp.report-shell title="Statement of Cash Flows" :subtitle="$subtitle" :hide-header="$hideHeader" :show-extra-filters="$showExtraFilters" :sort-by="$sortBy" :date-preset-options="$datePresetOptions" :sort-by-options="$sortByOptions" :show-email-modal="$showEmailModal">
    <x-slot:excel><x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button></x-slot:excel>
    <table class="be-report-table be-table">
        <tbody>
            <tr class="be-report-table__group"><td colspan="2">Operating activities</td></tr>
            <tr><td>Customer receipts</td><td class="num">{{ number_format($statement['receipts'], 2) }}</td></tr>
            <tr><td>Vendor payments</td><td class="num">{{ number_format($statement['payments'], 2) }}</td></tr>
            <tr><td>Bank deposits recorded</td><td class="num">{{ number_format($statement['deposits'], 2) }}</td></tr>
            <tr class="be-report-table__total"><td>Net cash from operations</td><td class="num">{{ number_format($statement['net'], 2) }}</td></tr>
        </tbody>
    </table>
</x-erp.report-shell>
