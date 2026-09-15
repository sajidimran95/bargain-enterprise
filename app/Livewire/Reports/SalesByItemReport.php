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
#[Title('Sales by Item')]
class SalesByItemReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->applyDatePreset($this->datePreset);
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
                    number_format((float) ($line->invoice?->balance_due ?? 0), 2, '.', ''),
                ];
            }
            $flat[] = [
                'Total '.$group['item_label'],
                '',
                '',
                '',
                number_format((float) $group['qty'], 4, '.', ''),
                number_format((float) $group['amount'], 2, '.', ''),
                '',
            ];
        }

        return $this->exportReportCsv(
            'sales-by-item.csv',
            ['Item', 'Date', 'Num', 'Customer', 'Qty', 'Amount', 'Balance'],
            $flat
        );
    }

    /**
     * @return Collection<int, array{item_label: string, lines: Collection, qty: string, amount: string}>
     */
    protected function groupedRows(): Collection
    {
        $lines = InvoiceLine::query()
            ->with(['item', 'invoice.customer'])
            ->whereHas('invoice', function ($query) {
                $query->whereNot('status', 'draft')
                    ->whereDate('invoice_date', '>=', $this->from)
                    ->whereDate('invoice_date', '<=', $this->to);
            })
            ->orderBy('item_id')
            ->get()
            ->sortBy(fn (InvoiceLine $line) => $line->invoice?->invoice_date?->timestamp ?? 0)
            ->values();

        return $lines
            ->groupBy(fn (InvoiceLine $line) => $line->item_id ?: 'none')
            ->map(function (Collection $group) {
                $first = $group->first();
                $item = $first?->item;
                $qty = '0.0000';
                $amount = '0.00';
                foreach ($group as $line) {
                    $qty = bcadd($qty, (string) $line->quantity, 4);
                    $amount = bcadd($amount, (string) $line->amount, 2);
                }

                return [
                    'item_label' => $item
                        ? $item->sku.' — '.($item->sales_description ?: $item->name)
                        : 'Unassigned',
                    'lines' => $group->values(),
                    'qty' => $qty,
                    'amount' => $amount,
                ];
            })
            ->values();
    }

    public function render()
    {
        $groups = $this->groupedRows();
        $grandQty = '0.0000';
        $grandAmount = '0.00';
        foreach ($groups as $group) {
            $grandQty = bcadd($grandQty, $group['qty'], 4);
            $grandAmount = bcadd($grandAmount, $group['amount'], 2);
        }

        return view('livewire.reports.sales-by-item-report', [
            'groups' => $groups,
            'grandQty' => $grandQty,
            'grandAmount' => $grandAmount,
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'Sales by Item',
            'windowTitle' => 'Sales by Item',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Sales by Item';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'sales-by-item.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Date</th><th>Num</th><th>Customer</th><th class="num">Qty</th><th class="num">Amount</th></tr></thead><tbody>';
        foreach ($this->groupedRows() as $group) {
            $html .= '<tr class="group"><td colspan="5">'.e($group['item_label']).'</td></tr>';
            foreach ($group['lines'] as $line) {
                $html .= '<tr>'
                    .'<td>'.e($line->invoice?->invoice_date?->format('m/d/Y') ?? '').'</td>'
                    .'<td>'.e($line->invoice?->invoice_number ?? '').'</td>'
                    .'<td>'.e($line->invoice?->customer?->display_name ?? '').'</td>'
                    .'<td class="num">'.e(number_format((float) $line->quantity, 2)).'</td>'
                    .'<td class="num">'.e(number_format((float) $line->amount, 2)).'</td>'
                    .'</tr>';
            }
        }
        $html .= '</tbody></table>';

        return $html;
    }
}
