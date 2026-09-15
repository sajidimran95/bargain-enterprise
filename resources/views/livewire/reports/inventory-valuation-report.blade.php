<x-erp.report-shell title="Inventory Valuation Summary" :subtitle="$subtitle" :hide-header="$hideHeader" :show-extra-filters="$showExtraFilters" :sort-by="$sortBy" :date-preset-options="$datePresetOptions" :sort-by-options="$sortByOptions" :show-email-modal="$showEmailModal">
    <x-slot:excel><x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button></x-slot:excel>
    <x-slot:filters>
        <div class="be-field"><label class="be-field__label">Look for</label><x-erp.input wire:model.live.debounce.300ms="search" placeholder="SKU / name…" class="w-56" /></div>
    </x-slot:filters>
    <table class="be-report-table be-table be-table--line-select">
        <thead><tr><th>SKU</th><th>Name</th><th class="num">On Hand</th><th class="num">Avg Cost</th><th class="num">Asset Value</th><th class="num">Sales Price</th><th class="num">Retail Value</th></tr></thead>
        <tbody>
            @forelse ($rows as $row)
                <tr><td>{{ $row['sku'] }}</td><td>{{ $row['name'] }}</td><td class="num">{{ number_format($row['qty'], 2) }}</td><td class="num">{{ number_format($row['avg_cost'], 4) }}</td><td class="num">{{ number_format($row['asset_value'], 2) }}</td><td class="num">{{ number_format($row['sales_price'], 2) }}</td><td class="num">{{ number_format($row['retail_value'], 2) }}</td></tr>
            @empty
                <tr><td colspan="7">No inventory items.</td></tr>
            @endforelse
            @if ($rows->isNotEmpty())
                <tr class="be-report-table__total"><td colspan="4" class="text-right">TOTAL</td><td class="num">{{ number_format($assetTotal, 2) }}</td><td></td><td class="num">{{ number_format($retailTotal, 2) }}</td></tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
