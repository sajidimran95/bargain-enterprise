<div>
<x-erp.report-shell
    title="MSA Sales Report — {{ $layoutTitle }}"
    :subtitle="$subtitle"
    :show-basis="true"
    :basis="$basis"
    :hide-header="$hideHeader"
    :show-extra-filters="$showExtraFilters"
    :sort-by="$sortBy"
    :date-preset-options="$datePresetOptions"
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
        <div class="be-field">
            <label class="be-field__label">Excel / Report type</label>
            <select class="be-input w-72" wire:model.live="layout">
                @foreach ($layoutOptions as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="be-field">
            <label class="be-field__label">Scan / Look for</label>
            <input
                type="text"
                class="be-input be-scan-input w-72"
                wire:model.live.debounce.300ms="search"
                placeholder="Scan barcode / UPC or customer…"
                autocomplete="off"
                spellcheck="false"
            />
        </div>
    </x-slot:filters>

    @php
        $numericHeaders = \App\Support\QbSalesReportExport::numericHeaders();
    @endphp

    <div class="overflow-x-auto pr-2">
        <table class="be-report-table be-table be-table--line-select be-report-table--wide">
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        <th class="{{ in_array($header, $numericHeaders, true) ? 'num' : '' }}">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $row)
                    @php
                        $label = trim((string) ($row[0] ?? ''));
                        $othersEmpty = true;
                        foreach ($row as $i => $value) {
                            if ($i === 0) {
                                continue;
                            }
                            if (trim((string) $value) !== '') {
                                $othersEmpty = false;
                                break;
                            }
                        }
                        $isGroup = $label !== '' && $othersEmpty;
                        $isGrand = strtolower($label) === 'total';
                        $isTotal = str_starts_with(strtolower($label), 'total');
                        $rowClass = $isGroup ? 'be-report-table__group' : ($isGrand ? 'be-report-table__total' : ($isTotal ? 'be-report-table__subtotal' : ''));
                    @endphp
                    <tr class="{{ $rowClass }}">
                        @foreach ($row as $colIndex => $cell)
                            @php
                                $header = $headers[$colIndex] ?? '';
                                $isNum = in_array($header, $numericHeaders, true);
                            @endphp
                            <td class="{{ $isNum ? 'num' : '' }}">
                                @if ($isNum && $cell !== '' && $cell !== null && is_numeric($cell))
                                    {{ $header === 'Qty' ? number_format((float) $cell, 0) : number_format((float) $cell, 2) }}
                                @else
                                    {{ $cell }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ max(count($headers), 1) }}">No sales in this date range.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-erp.report-shell>

@if ($showModifyReport)
    <div class="be-report-popup" role="dialog" aria-modal="true" aria-labelledby="modify-report-title">
        <div class="be-report-popup__backdrop" wire:click="closeModifyReport"></div>
        <div class="be-report-popup__panel be-report-popup__panel--modify">
            <div class="be-modify-report__header">
                <button type="button" class="be-modify-report__close" wire:click="closeModifyReport" aria-label="Close">×</button>
                <h2 id="modify-report-title" class="be-modify-report__title">Modify Report: {{ $modifyTitle }}</h2>
            </div>

            <div class="be-modify-report__tabs" role="tablist">
                <button type="button" class="be-modify-report__tab {{ $modifyTab === 'display' ? 'be-modify-report__tab--active' : '' }}" wire:click="$set('modifyTab', 'display')">Display</button>
                <button type="button" class="be-modify-report__tab {{ $modifyTab === 'filters' ? 'be-modify-report__tab--active' : '' }}" wire:click="$set('modifyTab', 'filters')">Filters</button>
            </div>

            <div class="be-modify-report__body">
                @if ($modifyTab === 'display')
                    <p class="be-modify-report__legend">Report Date Range</p>
                    <div class="be-modify-report__dates">
                        <div class="be-field">
                            <label class="be-field__label">Dates</label>
                            <select class="be-input" wire:model.live="draftDatePreset">
                                @foreach ($datePresetOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="be-field">
                            <label class="be-field__label">From</label>
                            <input class="be-input" type="date" wire:model="draftFrom" wire:change="markDraftDatesCustom">
                        </div>
                        <div class="be-field">
                            <label class="be-field__label">To</label>
                            <input class="be-input" type="date" wire:model="draftTo" wire:change="markDraftDatesCustom">
                        </div>
                    </div>

                    <p class="be-modify-report__legend">Report Basis</p>
                    <div class="be-modify-report__basis">
                        <label><input type="radio" wire:model="draftBasis" value="accrual"> Accrual</label>
                        <label><input type="radio" wire:model="draftBasis" value="cash"> Cash</label>
                    </div>
                    <p class="be-modify-report__help">Accrual includes every invoice. Cash includes only invoices that are fully paid.</p>

                    <p class="be-modify-report__legend">Columns</p>
                    <div class="be-modify-report__columns">
                        <div>
                            <input class="be-input mb-2" type="text" wire:model.live.debounce.200ms="columnSearch" placeholder="Search Columns">
                            <div class="be-modify-report__checklist">
                                @foreach ($draftHeaders as $index => $header)
                                    @php
                                        $columnLabel = $header === '' ? '(left margin)' : $header;
                                    @endphp
                                    @if ($columnSearch === '' || str_contains(strtolower($columnLabel), strtolower($columnSearch)))
                                        <label class="be-modify-report__check" wire:key="modify-col-{{ $draftLayout }}-{{ $index }}">
                                            <input type="checkbox" value="{{ $index }}" wire:model="draftVisibleColumns">
                                            <span>{{ $columnLabel }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                            <p class="be-modify-report__help">Put a check mark next to each column that you want to appear in the report.</p>
                        </div>
                        <div class="be-modify-report__sort">
                            <div class="be-field">
                                <label class="be-field__label">Total by</label>
                                <select class="be-input" wire:model="draftTotalBy">
                                    @foreach ($totalByOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="be-field">
                                <label class="be-field__label">Sort by</label>
                                <select class="be-input" wire:model="draftSortBy">
                                    @foreach ($sortByOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="be-field__label">Sort in</p>
                            <label class="be-modify-report__check"><input type="radio" wire:model="draftSortDirection" value="asc"> Ascending order</label>
                            <label class="be-modify-report__check"><input type="radio" wire:model="draftSortDirection" value="desc"> Descending order</label>
                        </div>
                    </div>
                @else
                    <div class="be-modify-report__filters">
                        <div>
                            <p class="be-modify-report__legend">Choose Filter</p>
                            <button type="button" class="be-modify-report__filter {{ $filterChoice === 'type' ? 'be-modify-report__filter--active' : '' }}" wire:click="$set('filterChoice', 'type')">Report Type</button>
                            <button type="button" class="be-modify-report__filter {{ $filterChoice === 'name' ? 'be-modify-report__filter--active' : '' }}" wire:click="$set('filterChoice', 'name')">Name</button>
                            <button type="button" class="be-modify-report__filter {{ $filterChoice === 'item' ? 'be-modify-report__filter--active' : '' }}" wire:click="$set('filterChoice', 'item')">Item</button>
                        </div>
                        <div>
                            @if ($filterChoice === 'type')
                                <label class="be-field__label">Report type</label>
                                <select class="be-input" wire:model.live="draftLayout">
                                    @foreach ($layoutOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="be-modify-report__help">Sales by Customer Detail uses the same columns as the Nashville customer detail report.</p>
                            @else
                                <label class="be-field__label">{{ $filterChoice === 'item' ? 'Item' : 'Name' }}</label>
                                <input class="be-input" type="text" wire:model="draftSearch" placeholder="Customer, item, SKU, or barcode">
                                <p class="be-modify-report__help">Matches the customer name, item name, SKU, or barcode on each sales line.</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="be-modify-report__footer">
                <x-erp.button type="button" wire:click="revertModifyReport">Revert</x-erp.button>
                <x-erp.button type="button" variant="primary" wire:click="applyModifyReport">OK</x-erp.button>
                <x-erp.button type="button" wire:click="closeModifyReport">Cancel</x-erp.button>
            </div>
        </div>
    </div>
@endif
</div>
