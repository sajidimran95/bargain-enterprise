<x-erp.report-shell title="Profit & Loss" :subtitle="$subtitle" :show-basis="true" :basis="$basis" :hide-header="$hideHeader" :show-extra-filters="$showExtraFilters" :sort-by="$sortBy" :date-preset-options="$datePresetOptions" :sort-by-options="$sortByOptions" :show-email-modal="$showEmailModal"
    :show-comment-modal="$showCommentModal"
    :show-share-modal="$showShareModal"
    :show-memorize-modal="$showMemorizeModal"
    :report-comment="$reportComment"
    :share-url="$shareUrl"
    :memorize-name="$memorizeName"
    :email-subject="$emailSubject">
    <x-slot:excel><x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button></x-slot:excel>
    <table class="be-report-table be-table">
        <tbody>
            <tr class="be-report-table__group"><td colspan="2">Income</td></tr>
            @foreach ($statement['income_accounts'] as $row)
                <tr><td>{{ $row['name'] }}</td><td class="num">{{ number_format($row['amount'], 2) }}</td></tr>
            @endforeach
            <tr class="be-report-table__subtotal"><td>Total Income</td><td class="num">{{ number_format($statement['income'], 2) }}</td></tr>
            <tr class="be-report-table__group"><td colspan="2">Cost of Goods Sold</td></tr>
            @foreach ($statement['expense_accounts'] as $row)
                <tr><td>{{ $row['name'] }}</td><td class="num">{{ number_format($row['amount'], 2) }}</td></tr>
            @endforeach
            <tr class="be-report-table__subtotal"><td>Total COGS</td><td class="num">{{ number_format($statement['cogs'], 2) }}</td></tr>
            <tr class="be-report-table__total"><td>Gross Profit</td><td class="num">{{ number_format($statement['gross'], 2) }}</td></tr>
            <tr class="be-report-table__total"><td>Net Income</td><td class="num">{{ number_format($statement['net'], 2) }}</td></tr>
        </tbody>
    </table>
</x-erp.report-shell>
