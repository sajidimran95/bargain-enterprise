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
        $numericHeaders = ['Qty', 'Sales Price', 'Amount', 'Balance', '% of Sales', 'Avg Price', 'COGS', 'Avg COGS', 'Gross Margin', 'Gross Margin %'];
    @endphp

    <div class="overflow-x-auto">
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
