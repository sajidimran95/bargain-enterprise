<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\InvoiceLine;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('MSA Sales Report')]
class SalesByItemReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public string $search = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'last_week';
        $this->applyDatePreset('last_week');
        $this->sortBy = 'default';
    }

    public function exportExcel(): StreamedResponse
    {
        $flat = [];
        foreach ($this->groupedRows() as $group) {
            foreach ($group['lines'] as $line) {
                $flat[] = [
                    $group['item_label'],
                    $line->invoice?->invoice_date?->format('Y-m-d'),
                    $line->invoice?->invoice_number,
                    $line->invoice?->customer?->display_name,
                    number_format((float) $line->quantity, 4, '.', ''),
                    number_format((float) $line->amount, 2, '.', ''),
                    $this->lineBalanceShare($line),
                ];
            }
            $flat[] = [
                'Total '.$group['item_label'],
                '',
                '',
                '',
                number_format((float) $group['qty'], 4, '.', ''),
                number_format((float) $group['amount'], 2, '.', ''),
                number_format((float) $group['balance'], 2, '.', ''),
            ];
        }

        return $this->exportReportCsv(
            'sales-by-item.csv',
            ['Item', 'Date', 'Num', 'Name', 'Qty', 'Amount', 'Balance'],
            $flat
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
     * @return Collection<int, array{item_label: string, item_code: string, lines: Collection, qty: string, amount: string, balance: string}>
     */
    protected function groupedRows(): Collection
    {
        $lines = InvoiceLine::query()
            ->with(['item', 'invoice.customer'])
            ->whereHas('invoice', function ($query) {
                $query->whereNotIn('status', ['draft', 'pending'])
                    ->where('is_pending', false)
                    ->whereDate('invoice_date', '>=', $this->from)
                    ->whereDate('invoice_date', '<=', $this->to);
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

        $sorted = match ($this->sortBy) {
            'date' => $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->invoice_date?->timestamp ?? 0),
            'amount' => $lines->sortByDesc(fn (InvoiceLine $line) => (float) $line->amount),
            'num' => $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->invoice_number),
            'name' => $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->customer?->display_name ?? ''),
            default => $lines->sortBy([
                fn (InvoiceLine $line) => $line->item?->barcode ?: $line->item?->sku ?: '',
                fn (InvoiceLine $line) => $line->invoice?->invoice_date?->timestamp ?? 0,
            ]),
        };

        return $sorted
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
            'amount' => 'Amount',
        ];
    }

    public function render()
    {
        $groups = $this->groupedRows();
        $grandQty = '0.0000';
        $grandAmount = '0.00';
        $grandBalance = '0.00';
        foreach ($groups as $group) {
            $grandQty = bcadd($grandQty, $group['qty'], 4);
            $grandAmount = bcadd($grandAmount, $group['amount'], 2);
            $grandBalance = bcadd($grandBalance, $group['balance'], 2);
        }

        return view('livewire.reports.sales-by-item-report', [
            'groups' => $groups,
            'grandQty' => $grandQty,
            'grandAmount' => $grandAmount,
            'grandBalance' => $grandBalance,
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->salesSortOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'MSA Sales Report',
            'windowTitle' => 'MSA Sales Report',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'MSA Sales Report';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'msa-sales-report.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Date</th><th>Num</th><th>Name</th><th class="num">Qty</th><th class="num">Amount</th><th class="num">Balance</th></tr></thead><tbody>';
        foreach ($this->groupedRows() as $group) {
            $html .= '<tr class="group"><td colspan="6">'.e($group['item_label']).'</td></tr>';
            foreach ($group['lines'] as $line) {
                $html .= '<tr>'
                    .'<td>'.e($line->invoice?->invoice_date?->format('m/d/Y') ?? '').'</td>'
                    .'<td>'.e($line->invoice?->invoice_number ?? '').'</td>'
                    .'<td>'.e($line->invoice?->customer?->display_name ?? '').'</td>'
                    .'<td class="num">'.e(number_format((float) $line->quantity, 2)).'</td>'
                    .'<td class="num">'.e(number_format((float) $line->amount, 2)).'</td>'
                    .'<td class="num">'.e(number_format((float) $this->lineBalanceShare($line), 2)).'</td>'
                    .'</tr>';
            }
        }
        $html .= '</tbody></table>';

        return $html;
    }
}
