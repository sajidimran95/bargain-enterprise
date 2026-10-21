<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\InvoiceLine;
use App\Support\QbDatePresets;
use App\Support\QbSalesReportExport;
use App\Support\XlsxExporter;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('MSA Sales Report')]
class SalesByItemReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public string $search = '';

    #[Url]
    public string $layout = 'item_detail';

    public bool $showModifyReport = false;

    public string $modifyTab = 'display';

    public string $columnSearch = '';

    public string $filterChoice = 'name';

    /** @var list<int|string> */
    public array $visibleColumns = [];

    public string $totalBy = 'default';

    public string $sortDirection = 'asc';

    public string $draftLayout = 'item_detail';

    public string $draftDatePreset = 'last_week';

    public string $draftFrom = '';

    public string $draftTo = '';

    public string $draftBasis = 'accrual';

    public string $draftSortBy = 'default';

    public string $draftSortDirection = 'asc';

    public string $draftTotalBy = 'default';

    public string $draftSearch = '';

    /** @var list<int|string> */
    public array $draftVisibleColumns = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'last_week';
        $this->applyDatePreset('last_week');
        $this->sortBy = 'default';

        if (! array_key_exists($this->layout, QbSalesReportExport::layouts())) {
            $this->layout = 'item_detail';
        }

        $this->visibleColumns = $this->columnIndexes($this->layout);
    }

    public function updatedLayout(string $value): void
    {
        if (! array_key_exists($value, QbSalesReportExport::layouts())) {
            $this->layout = 'item_detail';
        }

        $this->visibleColumns = $this->columnIndexes($this->layout);
    }

    public function updatedDraftLayout(string $value): void
    {
        if (! array_key_exists($value, QbSalesReportExport::layouts())) {
            $this->draftLayout = 'item_detail';
        }

        $this->draftVisibleColumns = $this->columnIndexes($this->draftLayout);
    }

    public function updatedDraftDatePreset(string $value): void
    {
        if ($value === 'custom') {
            return;
        }

        [$from, $to] = QbDatePresets::dateStrings($value);
        $this->draftFrom = $from;
        $this->draftTo = $to;
    }

    public function markDraftDatesCustom(): void
    {
        $this->draftDatePreset = 'custom';
    }

    public function customizeReport(): void
    {
        $this->draftLayout = $this->layout;
        $this->draftDatePreset = $this->datePreset;
        $this->draftFrom = $this->from;
        $this->draftTo = $this->to;
        $this->draftBasis = $this->basis;
        $this->draftSortBy = $this->sortBy;
        $this->draftSortDirection = $this->sortDirection;
        $this->draftTotalBy = $this->totalBy;
        $this->draftSearch = $this->search;
        $this->draftVisibleColumns = $this->visibleColumns === []
            ? $this->columnIndexes($this->layout)
            : array_map('strval', $this->visibleColumns);
        $this->columnSearch = '';
        $this->modifyTab = 'display';
        $this->showModifyReport = true;
    }

    public function closeModifyReport(): void
    {
        $this->showModifyReport = false;
    }

    public function revertModifyReport(): void
    {
        $this->draftDatePreset = 'last_week';
        [$this->draftFrom, $this->draftTo] = QbDatePresets::dateStrings('last_week');
        $this->draftBasis = 'accrual';
        $this->draftSortBy = 'default';
        $this->draftSortDirection = 'asc';
        $this->draftTotalBy = 'default';
        $this->draftSearch = '';
        $this->draftVisibleColumns = $this->columnIndexes($this->draftLayout);
    }

    public function applyModifyReport(): void
    {
        if (! array_key_exists($this->draftLayout, QbSalesReportExport::layouts())) {
            $this->draftLayout = 'item_detail';
        }

        $this->layout = $this->draftLayout;
        $this->datePreset = $this->draftDatePreset;
        $this->from = $this->draftFrom;
        $this->to = $this->draftTo;
        $this->basis = in_array($this->draftBasis, ['accrual', 'cash'], true) ? $this->draftBasis : 'accrual';
        $this->sortBy = array_key_exists($this->draftSortBy, $this->salesSortOptions()) ? $this->draftSortBy : 'default';
        $this->sortDirection = $this->draftSortDirection === 'desc' ? 'desc' : 'asc';
        $this->totalBy = in_array($this->draftTotalBy, ['default', 'customer', 'item', 'class', 'rep', 'none'], true)
            ? $this->draftTotalBy
            : 'default';
        $this->search = $this->draftSearch;

        $allowed = array_keys(QbSalesReportExport::headersFor($this->layout));
        $selected = array_values(array_intersect(array_map('intval', $this->draftVisibleColumns), $allowed));
        $this->visibleColumns = array_map('strval', $selected === [] ? $allowed : $selected);
        $this->showModifyReport = false;
        $this->shareUrl = $this->buildShareUrl();
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $payload = $this->reportPayload();

        return app(XlsxExporter::class)->download(
            QbSalesReportExport::filename($this->layout),
            $payload['headers'],
            $payload['rows'],
            title: QbSalesReportExport::title($this->layout),
            subtitle: $this->reportPeriodLabel(),
            qbLayout: true,
        );
    }

    /**
     * Attribute open AR balance to this line by amount share of the invoice total.
     */
    public function lineBalanceShare(InvoiceLine $line): string
    {
        $invoice = $line->invoice;
        if (! $invoice) {
            return '0.00';
        }

        $total = (string) $invoice->total;
        $balance = (string) $invoice->balance_due;
        if (bccomp($total, '0', 2) <= 0 || bccomp($balance, '0', 2) === 0) {
            return '0.00';
        }

        return number_format(
            (float) bcmul($balance, bcdiv((string) $line->amount, $total, 8), 8),
            2,
            '.',
            ''
        );
    }

    /**
     * @return Collection<int, InvoiceLine>
     */
    protected function exportLines(): Collection
    {
        return $this->queryLines()->values();
    }

    /**
     * @return Collection<int, InvoiceLine>
     */
    protected function queryLines(): Collection
    {
        $lines = InvoiceLine::query()
            ->with([
                'item.unitOfMeasure',
                'item.itemType',
                'item.category',
                'invoice.customer.taxCode',
                'invoice.createdBy',
                'invoice.paymentAllocations.payment',
            ])
            ->whereHas('invoice', function ($query) {
                $query->whereNotIn('status', ['draft', 'pending'])
                    ->where('is_pending', false)
                    ->whereDate('invoice_date', '>=', $this->from)
                    ->whereDate('invoice_date', '<=', $this->to)
                    ->when($this->basis === 'cash', function ($paid) {
                        $paid->where(function ($settled) {
                            $settled->where('balance_due', '<=', 0)
                                ->orWhere('status', 'paid');
                        });
                    });
            })
            ->when(filled($this->search), function ($query) {
                $term = trim($this->search);
                $like = '%'.$term.'%';
                $query->where(function ($q) use ($term, $like) {
                    $q->whereHas('item', function ($itemQuery) use ($term, $like) {
                        $itemQuery->where('barcode', $term)
                            ->orWhere('sku', $term)
                            ->orWhere('barcode', 'like', $like)
                            ->orWhere('sku', 'like', $like)
                            ->orWhere('name', 'like', $like)
                            ->orWhere('sales_description', 'like', $like);
                    })->orWhereHas('invoice.customer', function ($customerQuery) use ($like) {
                        $customerQuery->where('display_name', 'like', $like);
                    });
                });
            })
            ->get();

        $descending = $this->sortDirection === 'desc';

        return match ($this->sortBy) {
            'date' => $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->invoice_date?->timestamp ?? 0, SORT_REGULAR, $descending),
            'amount' => $lines->sortBy(fn (InvoiceLine $line) => (float) $line->amount, SORT_REGULAR, $descending),
            'num' => $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->invoice_number, SORT_REGULAR, $descending),
            'name' => $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->customer?->display_name ?? '', SORT_REGULAR, $descending),
            'item' => $lines->sortBy(fn (InvoiceLine $line) => $line->item?->name ?? '', SORT_REGULAR, $descending),
            default => $lines->sortBy([
                fn (InvoiceLine $line) => $line->item?->itemType?->label
                    ?: $line->item?->type
                    ?: 'Inventory',
                fn (InvoiceLine $line) => $line->item?->barcode ?: $line->item?->sku ?: '',
                fn (InvoiceLine $line) => $line->invoice?->invoice_date?->timestamp ?? 0,
            ], SORT_REGULAR, $descending),
        };
    }

    /**
     * @return Collection<int, array{item_label: string, item_code: string, lines: Collection, qty: string, amount: string, balance: string, type_label: string}>
     */
    protected function groupedRows(): Collection
    {
        return $this->queryLines()
            ->values()
            ->groupBy(fn (InvoiceLine $line) => $line->item_id ?: 'none')
            ->map(function (Collection $group) {
                $first = $group->first();
                $item = $first?->item;
                $code = $item?->barcode ?: $item?->sku ?: 'Unassigned';
                $description = $item?->sales_description ?: $item?->name ?: 'Unassigned';
                $qty = '0.0000';
                $amount = '0.00';
                $balance = '0.00';
                foreach ($group as $line) {
                    $qty = bcadd($qty, (string) $line->quantity, 4);
                    $amount = bcadd($amount, (string) $line->amount, 2);
                    $balance = bcadd($balance, $this->lineBalanceShare($line), 2);
                }

                return [
                    'item_code' => (string) $code,
                    'item_label' => $item
                        ? $code.' ('.$description.')'
                        : 'Unassigned',
                    'type_label' => (string) (
                        $item?->itemType?->label
                        ?: $item?->type
                        ?: 'Inventory'
                    ),
                    'lines' => $group->values(),
                    'qty' => $qty,
                    'amount' => $amount,
                    'balance' => $balance,
                ];
            })
            ->values();
    }

    /**
     * @return array<string, string>
     */
    protected function salesSortOptions(): array
    {
        return [
            'default' => 'Default',
            'date' => 'Date',
            'num' => 'Num',
            'name' => 'Name',
            'item' => 'Item',
            'amount' => 'Amount',
        ];
    }

    public function render()
    {
        $payload = $this->reportPayload();
        $draftHeaders = QbSalesReportExport::headersFor($this->draftLayout);

        return view('livewire.reports.sales-by-item-report', [
            'headers' => $payload['headers'],
            'rows' => $payload['rows'],
            'draftHeaders' => $draftHeaders,
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->salesSortOptions(),
            'layoutOptions' => QbSalesReportExport::layouts(),
            'totalByOptions' => $this->totalByOptions(),
            'modifyTitle' => QbSalesReportExport::title($this->draftLayout),
            'subtitle' => $this->reportPeriodLabel(),
            'layoutTitle' => QbSalesReportExport::title($this->layout),
        ])->layoutData([
            'title' => 'MSA Sales Report',
            'windowTitle' => 'MSA Sales Report',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return QbSalesReportExport::title($this->layout);
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return str_replace('.xlsm', '.pdf', QbSalesReportExport::filename($this->layout));
    }

    protected function reportPdfBodyHtml(): string
    {
        $payload = $this->reportPayload();
        $html = '<table><thead><tr>';
        foreach ($payload['headers'] as $header) {
            if ($header === '') {
                continue;
            }
            $html .= '<th>'.e($header).'</th>';
        }
        $html .= '</tr></thead><tbody>';
        foreach ($payload['rows'] as $row) {
            $html .= '<tr>';
            foreach ($row as $index => $cell) {
                if (($payload['headers'][$index] ?? null) === '' && $index > 0) {
                    continue;
                }
                $html .= '<td>'.e((string) $cell).'</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }

    /**
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function reportPayload(): array
    {
        $payload = app(QbSalesReportExport::class)->build(
            $this->layout,
            $this->exportLines(),
            $this->totalBy,
            $this->sortBy,
            $this->sortDirection,
        );

        return QbSalesReportExport::selectColumns($payload, $this->visibleColumns);
    }

    /**
     * @return list<string>
     */
    protected function columnIndexes(string $layout): array
    {
        return array_map('strval', array_keys(QbSalesReportExport::headersFor($layout)));
    }

    /**
     * @return array<string, string>
     */
    protected function totalByOptions(): array
    {
        return [
            'default' => 'Default',
            'customer' => 'Customer',
            'item' => 'Item',
            'class' => 'Class',
            'rep' => 'Sales Rep',
            'none' => 'Total only',
        ];
    }
}
