<x-erp.report-shell
    title="MSA Inventory"
    :show-dates="false"
    :show-filter-bar="true"
    :hide-header="$hideHeader"
    :show-extra-filters="$showExtraFilters"
    :sort-by="$sortBy"
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

    <x-slot:filters>
        <div class="be-stock-search">
            <label class="be-stock-search__label">Look for</label>
            <input
                type="text"
                class="be-input be-scan-input be-stock-search__input"
                wire:model="search"
                wire:keydown.enter.prevent="runSearch"
                placeholder=""
                autocomplete="off"
                spellcheck="false"
            />
            <x-erp.select
                wire:model="searchField"
                class="be-stock-search__field"
                :options="[
                    'all' => 'All fields',
                    'sku' => 'Name / Barcode',
                    'name' => 'Name',
                    'description' => 'Description',
                ]"
            />
            <button type="button" class="be-btn be-btn--primary" wire:click="runSearch">Search</button>
            <button type="button" class="be-btn" wire:click="resetSearch">Reset</button>
            <label class="be-stock-search__check">
                <input type="checkbox" wire:model="searchWithinResults"> Search within results
            </label>
            <label class="be-stock-search__check">
                <input type="checkbox" wire:model.live="includeInactive"> Include inactive
            </label>
        </div>
    </x-slot:filters>

    <table class="be-report-table be-table be-table--line-select be-report-table--wide be-stock-table">
        <thead>
            <tr>
                <th class="be-stock-table__status" aria-label="Status"></th>
                <th>NAME</th>
                <th>DESCRIPTION</th>
                <th>TYPE</th>
                <th>ITEM TYPE</th>
                <th class="num">TOTAL QUANTITY ON HAND</th>
                <th class="num">PRICE</th>
                <th class="num">COST</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null }">
            @forelse ($rows as $item)
                <tr
                    wire:key="inv-stock-{{ $item->id }}"
                    @click="selectedLine = 'item-{{ $item->id }}'"
                    :class="selectedLine === 'item-{{ $item->id }}' ? 'is-selected' : ''"
                    class="{{ $loop->even ? 'be-row-alt' : '' }}"
                >
                    <td class="be-stock-table__status">
                        <span class="be-stock-table__diamond {{ $item->is_active ? '' : 'is-inactive' }}"></span>
                    </td>
                    <td class="font-mono">{{ $item->barcode ?: $item->sku }}</td>
                    <td>{{ $item->sales_description ?: $item->name }}</td>
                    <td>{{ str($item->type ?: 'inventory_part')->replace('_', ' ')->title() }}</td>
                    <td>{{ $item->itemType?->label }}</td>
                    <td class="num">{{ number_format((float) $item->on_hand, 0) }}</td>
                    <td class="num">{{ number_format((float) $item->sales_price, 2) }}</td>
                    <td class="num">{{ number_format((float) $item->purchase_cost, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="8">No inventory items.</td></tr>
            @endforelse
        </tbody>
    </table>
</x-erp.report-shell>
