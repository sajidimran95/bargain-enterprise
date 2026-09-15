<x-erp.report-shell
    title="Inventory / Stock"
    :show-dates="false"
    :hide-header="$hideHeader"
    :show-email-modal="$showEmailModal"
>
    <x-slot:excel>
        <x-erp.button type="button" wire:click="exportExcel">Excel ▾</x-erp.button>
    </x-slot:excel>

    <table class="be-report-table be-table be-table--line-select">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="text-right">Qty OH</th>
                <th>Category</th>
                <th class="text-right">Items/Container</th>
                <th>Promotion</th>
                <th class="text-right">Price</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null }">
            @forelse ($rows as $item)
                <tr
                    @click="selectedLine = 'item-{{ $item->id }}'"
                    :class="selectedLine === 'item-{{ $item->id }}' ? 'is-selected' : ''"
                >
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->sales_description ?: $item->name }}</td>
                    <td class="num {{ (float) $item->on_hand < 0 ? 'text-red-700' : '' }}">{{ number_format((float) $item->on_hand, 2) }}</td>
                    <td>{{ $item->category?->code }}</td>
                    <td class="num">{{ $item->items_per_container !== null ? number_format((float) $item->items_per_container, 2) : '' }}</td>
                    <td>{{ $item->promotion }}</td>
                    <td class="num">{{ number_format((float) $item->sales_price, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No inventory items.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-erp.report-shell>
