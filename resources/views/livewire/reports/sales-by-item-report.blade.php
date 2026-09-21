<x-erp.report-shell
    title="MSA Sales Report"
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

    <table class="be-report-table be-table be-table--line-select be-report-table--wide">
        <thead>
            <tr>
                <th>Date</th>
                <th>Num</th>
                <th>Name</th>
                <th class="num">Qty</th>
                <th class="num">Amount</th>
                <th class="num">Balance</th>
            </tr>
        </thead>
        <tbody x-data="{ selectedLine: null, collapsed: {} }">
            @forelse ($groups as $group)
                @php($groupKey = 'g-'.$loop->index)
                <tr
                    class="be-report-table__group"
                    @click="collapsed['{{ $groupKey }}'] = !collapsed['{{ $groupKey }}']"
                >
                    <td colspan="6">
                        <span
                            class="be-report-table__group-toggle"
                            x-text="collapsed['{{ $groupKey }}'] ? '▶' : '▼'"
                        ></span>
                        {{ $group['item_label'] }}
                    </td>
                </tr>
                @foreach ($group['lines'] as $line)
                    <tr
                        wire:key="sale-line-{{ $line->id }}"
                        x-show="!collapsed['{{ $groupKey }}']"
                        @click="selectedLine = 'line-{{ $line->id }}'"
                        :class="selectedLine === 'line-{{ $line->id }}' ? 'is-selected' : ''"
                    >
                        <td>{{ $line->invoice?->invoice_date?->format('m/d/Y') }}</td>
                        <td>{{ $line->invoice?->invoice_number }}</td>
                        <td>{{ $line->invoice?->customer?->display_name }}</td>
                        <td class="num">{{ number_format((float) $line->quantity, 0) }}</td>
                        <td class="num">{{ number_format((float) $line->amount, 2) }}</td>
                        <td class="num">{{ number_format((float) $this->lineBalanceShare($line), 2) }}</td>
                    </tr>
                @endforeach
                <tr class="be-report-table__subtotal" x-show="!collapsed['{{ $groupKey }}']">
                    <td colspan="3" class="text-right">Total {{ $group['item_label'] }}</td>
                    <td class="num">{{ number_format((float) $group['qty'], 0) }}</td>
                    <td class="num">{{ number_format((float) $group['amount'], 2) }}</td>
                    <td class="num">{{ number_format((float) $group['balance'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No sales in this date range.</td></tr>
            @endforelse
            @if ($groups->isNotEmpty())
                <tr class="be-report-table__total">
                    <td colspan="3" class="text-right">TOTAL</td>
                    <td class="num">{{ number_format((float) $grandQty, 0) }}</td>
                    <td class="num">{{ number_format((float) $grandAmount, 2) }}</td>
                    <td class="num">{{ number_format((float) $grandBalance, 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
