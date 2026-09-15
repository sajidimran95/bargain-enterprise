<x-erp.report-shell title="General Ledger" :subtitle="$subtitle" :hide-header="$hideHeader" :show-extra-filters="$showExtraFilters" :sort-by="$sortBy" :date-preset-options="$datePresetOptions" :sort-by-options="$sortByOptions" :show-email-modal="$showEmailModal">
    <x-slot:excel><x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button></x-slot:excel>
    <table class="be-report-table be-table be-table--line-select">
        <thead><tr><th>Date</th><th>Account</th><th>Memo</th><th class="num">Debit</th><th class="num">Credit</th></tr></thead>
        <tbody>
            @forelse ($rows as $row)
                <tr><td>{{ $row['date'] }}</td><td>{{ $row['account'] }}</td><td>{{ $row['memo'] }}</td><td class="num">{{ number_format($row['debit'], 2) }}</td><td class="num">{{ number_format($row['credit'], 2) }}</td></tr>
            @empty
                <tr><td colspan="5">No ledger entries in this period.</td></tr>
            @endforelse
            @if ($rows->isNotEmpty())
                <tr class="be-report-table__total"><td colspan="3" class="text-right">TOTAL</td><td class="num">{{ number_format($debitTotal, 2) }}</td><td class="num">{{ number_format($creditTotal, 2) }}</td></tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
