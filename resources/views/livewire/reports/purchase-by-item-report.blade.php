<x-erp.report-shell title="Purchases by Item" :subtitle="$subtitle" :hide-header="$hideHeader" :show-extra-filters="$showExtraFilters" :sort-by="$sortBy" :date-preset-options="$datePresetOptions" :sort-by-options="$sortByOptions" :show-email-modal="$showEmailModal"
    :show-comment-modal="$showCommentModal"
    :show-share-modal="$showShareModal"
    :show-memorize-modal="$showMemorizeModal"
    :report-comment="$reportComment"
    :share-url="$shareUrl"
    :memorize-name="$memorizeName"
    :email-subject="$emailSubject">
    <x-slot:excel><x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button></x-slot:excel>
    <table class="be-report-table be-table be-table--line-select">
        <thead><tr><th>SKU</th><th>Item</th><th class="num">Qty</th><th class="num">Amount</th></tr></thead>
        <tbody>
            @forelse ($rows as $row)
                <tr><td>{{ $row['sku'] }}</td><td>{{ $row['name'] }}</td><td class="num">{{ number_format($row['qty'], 2) }}</td><td class="num">{{ number_format($row['amount'], 2) }}</td></tr>
            @empty
                <tr><td colspan="4">No purchases in this period.</td></tr>
            @endforelse
            @if ($rows->isNotEmpty())
                <tr class="be-report-table__total"><td colspan="2" class="text-right">TOTAL</td><td class="num">{{ number_format($grandQty, 2) }}</td><td class="num">{{ number_format($grandAmount, 2) }}</td></tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
