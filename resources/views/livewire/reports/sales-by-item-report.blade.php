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
                    <div class="be-modify-report__filters" x-data="{ q: '' }">
                        <div>
                            <p class="be-modify-report__legend">Choose Filter</p>
                            <input type="text" class="be-input mb-2" x-model="q" placeholder="Search filters">
                            <div class="be-modify-report__filter-list">
                                <button type="button" class="be-modify-report__filter {{ $filterChoice === 'report_type' ? 'be-modify-report__filter--active' : '' }}" x-show="('report type').includes(q.toLowerCase())" wire:click="$set('filterChoice', 'report_type')">Report Type</button>
                                @foreach ($filterFields as $key => $field)
                                    <button type="button" class="be-modify-report__filter {{ $filterChoice === $key ? 'be-modify-report__filter--active' : '' }}" x-show="({{ \Illuminate\Support\Js::from(mb_strtolower($field['label'])) }}).includes(q.toLowerCase())" wire:click="$set('filterChoice', '{{ $key }}')">{{ $field['label'] }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            @if ($filterChoice === 'report_type')
                                <label class="be-field__label">Report type</label>
                                <select class="be-input" wire:model.live="draftLayout">
                                    @foreach ($layoutOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                <p class="be-modify-report__help">Sales by Customer Detail uses the same columns as the Nashville customer detail report.</p>
                            @elseif ($editor = ($filterFields[$filterChoice] ?? null))
                                <label class="be-field__label">{{ $editor['label'] }}</label>
                                @switch($editor['type'])
                                    @case('multi')
                                        @php $options = $filterOptions['fields'][$filterChoice] ?? []; @endphp
                                        <div x-data="{ find: '' }">
                                            @if (count($options) > 8)
                                                <input type="text" class="be-input mb-2" x-model="find" placeholder="Search">
                                            @endif
                                            <div class="be-modify-report__checklist">
                                                @forelse ($options as $optionValue => $optionLabel)
                                                    <label class="be-modify-report__check" x-show="({{ \Illuminate\Support\Js::from(mb_strtolower((string) $optionLabel)) }}).includes(find.toLowerCase())">
                                                        <input type="checkbox" value="{{ $optionValue }}" wire:model.live="draftFilters.extra.{{ $filterChoice }}">
                                                        {{ $optionLabel }}
                                                    </label>
                                                @empty
                                                    <p class="be-modify-report__help">No {{ strtolower($editor['label']) }} values yet.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                        @if (count($options) > 1)
                                            <div class="mt-2 flex gap-2">
                                                <button type="button" class="be-modify-report__mini" wire:click="selectAllFilter('{{ $filterChoice }}')">Select All</button>
                                                <button type="button" class="be-modify-report__mini" wire:click="clearDraftFilter('{{ $filterChoice }}')">Clear All</button>
                                            </div>
                                        @endif
                                        @break
                                    @case('choice')
                                        <select class="be-input" wire:model.live="draftFilters.extra.{{ $filterChoice }}">
                                            <option value="">{{ $editor['all'] ?? 'All' }}</option>
                                            @foreach ($editor['options'] as $optionValue => $optionLabel)
                                                <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
                                            @endforeach
                                        </select>
                                        @break
                                    @case('text')
                                        <input type="text" class="be-input" wire:model.blur="draftFilters.extra.{{ $filterChoice }}">
                                        @break
                                    @case('number')
                                        <div class="grid grid-cols-[8rem_1fr] gap-2">
                                            <select class="be-input" wire:model.live="draftFilters.extra.{{ $filterChoice }}.op">
                                                <option value="">Any</option>
                                                @foreach (['>=', '<=', '='] as $op)
                                                    <option value="{{ $op }}">{{ $op }}</option>
                                                @endforeach
                                            </select>
                                            <input type="number" step="any" class="be-input" wire:model.blur="draftFilters.extra.{{ $filterChoice }}.value">
                                        </div>
                                        @break
                                    @case('range')
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <p class="be-field__label">From</p>
                                                <input type="date" class="be-input" wire:model.live="draftFilters.extra.{{ $filterChoice }}.from">
                                            </div>
                                            <div>
                                                <p class="be-field__label">To</p>
                                                <input type="date" class="be-input" wire:model.live="draftFilters.extra.{{ $filterChoice }}.to">
                                            </div>
                                        </div>
                                        @break
                                    @case('builtin')
                                        @switch($filterChoice)
                                            @case('date')
                                                <select class="be-input mb-2" wire:model.live="draftDatePreset">
                                                    @foreach ($datePresetOptions as $value => $label)
                                                        <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <p class="be-field__label">From</p>
                                                        <input type="date" class="be-input" wire:model="draftFrom" wire:change="markDraftDatesCustom">
                                                    </div>
                                                    <div>
                                                        <p class="be-field__label">To</p>
                                                        <input type="date" class="be-input" wire:model="draftTo" wire:change="markDraftDatesCustom">
                                                    </div>
                                                </div>
                                                <p class="be-modify-report__help">Same as the report date range on the Display tab.</p>
                                                @break
                                            @case('class')
                                                <div x-data="{ find: '' }">
                                                    <input type="text" class="be-input mb-2" x-model="find" placeholder="Search">
                                                    <div class="be-modify-report__checklist" id="mr_class_list">
                                                        @forelse ($filterOptions['classes'] as $id => $name)
                                                            <label class="be-modify-report__check" x-show="({{ \Illuminate\Support\Js::from(mb_strtolower((string) $name)) }}).includes(find.toLowerCase())">
                                                                <input type="checkbox" value="{{ $id }}" wire:model.live="draftFilters.class">
                                                                {{ $name }}
                                                            </label>
                                                        @empty
                                                            <p class="be-modify-report__help">No classes yet.</p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                @if (count($filterOptions['classes']) > 1)
                                                    <div class="mt-2 flex gap-2">
                                                        <button type="button" class="be-modify-report__mini" wire:click="selectAllFilter('class')">Select All</button>
                                                        <button type="button" class="be-modify-report__mini" wire:click="clearDraftFilter('class')">Clear All</button>
                                                    </div>
                                                @endif
                                                <p class="be-modify-report__help">Choose a class (the product category) or several classes from the list.</p>
                                                @break
                                            @case('group')
                                                <div class="be-modify-report__checklist">
                                                    @foreach ($filterOptions['groups'] as $id => $name)
                                                        <label class="be-modify-report__check">
                                                            <input type="checkbox" value="{{ $id }}" wire:model.live="draftFilters.group">
                                                            {{ $name }}
                                                        </label>
                                                    @endforeach
                                                </div>
                                                <div class="mt-2 flex gap-2">
                                                    <button type="button" class="be-modify-report__mini" wire:click="selectAllFilter('group')">Select All</button>
                                                    <button type="button" class="be-modify-report__mini" wire:click="clearDraftFilter('group')">Clear All</button>
                                                </div>
                                                <p class="be-modify-report__help">Inside TN or Outside TN, from the customer's Name State.</p>
                                                @break
                                            @case('item')
                                                <select class="be-input mb-2" wire:model.live="draftFilters.item_type">
                                                    <option value="">All sales items</option>
                                                    <option value="inventory">All inventory items</option>
                                                    <option value="service">All services</option>
                                                </select>
                                                <input type="text" class="be-input" wire:model.live.debounce.300ms="itemLookup" placeholder="Search item name or SKU" autocomplete="off">
                                                @if (trim($itemLookup) !== '')
                                                    <div class="be-modify-report__checklist mt-2">
                                                        @forelse ($filterOptions['items']['matches'] as $id => $name)
                                                            <label class="be-modify-report__check">
                                                                <input type="checkbox" value="{{ $id }}" wire:model.live="draftFilters.item">
                                                                {{ $name }}
                                                            </label>
                                                        @empty
                                                            <p class="be-modify-report__help">No matching items.</p>
                                                        @endforelse
                                                    </div>
                                                @endif
                                                @if ($filterOptions['items']['selected'] !== [])
                                                    <p class="be-field__label mt-2">Selected</p>
                                                    <div class="be-modify-report__checklist">
                                                        @foreach ($filterOptions['items']['selected'] as $id => $name)
                                                            <label class="be-modify-report__check">
                                                                <input type="checkbox" value="{{ $id }}" wire:model.live="draftFilters.item">
                                                                {{ $name }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <p class="be-modify-report__help">Type a name or SKU. Matches appear under the search box. Chosen items stay listed below it. Leave the search empty to include every item of that type.</p>
                                                @break
                                            @case('name')
                                                <select class="be-input" wire:model.live="draftFilters.name">
                                                    <option value="">All customers/jobs</option>
                                                    @foreach ($filterOptions['customers'] as $id => $name)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                @break
                                            @case('state')
                                                <input type="text" class="be-input" wire:model.blur="draftFilters.state" placeholder="e.g. TN">
                                                <p class="be-modify-report__help">Enter the words (or characters) that must be in the Name State field.</p>
                                                @break
                                        @endswitch
                                        @break
                                @endswitch
                                @if (! empty($editor['help']) && $editor['type'] !== 'builtin')
                                    <p class="be-modify-report__help">{{ $editor['help'] }}</p>
                                @endif
                            @endif
                        </div>
                        <div>
                            <p class="be-modify-report__legend">Current Filter Choices</p>
                            <table class="be-modify-report__choice-table">
                                <thead>
                                    <tr>
                                        <th>Filter</th>
                                        <th>Set To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($filterChoices as $key => $choice)
                                        @continue(! $choice[3] && ! in_array($key, ['date', 'item', 'name', 'report_type'], true))
                                        <tr class="{{ $filterChoice === $key ? 'be-modify-report__choice-row--active' : '' }}" title="{{ $choice[2] }}" wire:click="$set('filterChoice', '{{ $key }}')">
                                            <td>{{ $choice[0] }}</td>
                                            <td>{{ $choice[1] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button type="button" class="be-modify-report__mini mt-2 w-full" wire:click="clearDraftFilter">Remove Selected Filter</button>
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
