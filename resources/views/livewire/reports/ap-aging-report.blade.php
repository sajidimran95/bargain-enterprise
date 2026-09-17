<x-erp.report-shell
    title="A/P Aging Summary"
    :subtitle="$subtitle"
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

    <table class="be-report-table be-table be-table--line-select">
        <thead>
            <tr>
                <th>Vendor</th>
                <th class="num">Current</th>
                <th class="num">1 - 30</th>
                <th class="num">31 - 60</th>
                <th class="num">61 - 90</th>
                <th class="num">&gt; 90</th>
                <th class="num">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr wire:key="ap-{{ $loop->index }}">
                    <td>{{ $row['vendor'] }}</td>
                    <td class="num">{{ number_format($row['current'], 2) }}</td>
                    <td class="num">{{ number_format($row['days_1_30'], 2) }}</td>
                    <td class="num">{{ number_format($row['days_31_60'], 2) }}</td>
                    <td class="num">{{ number_format($row['days_61_90'], 2) }}</td>
                    <td class="num">{{ number_format($row['days_90_plus'], 2) }}</td>
                    <td class="num">{{ number_format($row['total'], 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No open payables.</td></tr>
            @endforelse
            @if ($rows->isNotEmpty())
                <tr class="be-report-table__total">
                    <td class="text-right">TOTAL</td>
                    <td class="num">{{ number_format($totals['current'], 2) }}</td>
                    <td class="num">{{ number_format($totals['days_1_30'], 2) }}</td>
                    <td class="num">{{ number_format($totals['days_31_60'], 2) }}</td>
                    <td class="num">{{ number_format($totals['days_61_90'], 2) }}</td>
                    <td class="num">{{ number_format($totals['days_90_plus'], 2) }}</td>
                    <td class="num">{{ number_format($totals['total'], 2) }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</x-erp.report-shell>
