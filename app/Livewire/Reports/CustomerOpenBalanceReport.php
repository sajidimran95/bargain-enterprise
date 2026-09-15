<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Invoice;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Customer Open Balance')]
class CustomerOpenBalanceReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'this_year';
        $this->applyDatePreset('this_year');
    }

    public function exportExcel(): StreamedResponse
    {
        $flat = [];
        foreach ($this->groupedRows() as $group) {
            foreach ($group['invoices'] as $invoice) {
                $flat[] = [
                    $group['customer_label'],
                    'Invoice',
                    $invoice->invoice_date?->format('Y-m-d'),
                    $invoice->invoice_number,
                    $invoice->memo,
                    $invoice->due_date?->format('Y-m-d'),
                    number_format((float) $invoice->balance_due, 2, '.', ''),
                    number_format((float) $invoice->total, 2, '.', ''),
                ];
            }
            $flat[] = [
                'Total '.$group['customer_label'],
                '',
                '',
                '',
                '',
                '',
                number_format((float) $group['open_balance'], 2, '.', ''),
                number_format((float) $group['amount'], 2, '.', ''),
            ];
        }

        return $this->exportReportCsv(
            'customer-open-balance.csv',
            ['Customer', 'Type', 'Date', 'Num', 'Memo', 'Due Date', 'Open Balance', 'Amount'],
            $flat
        );
    }

    /**
     * @return Collection<int, array{customer_label: string, invoices: Collection, open_balance: string, amount: string}>
     */
    protected function groupedRows(): Collection
    {
        $query = Invoice::query()
            ->with('customer')
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->whereDate('invoice_date', '>=', $this->from)
            ->whereDate('invoice_date', '<=', $this->to)
            ->orderBy('customer_id');

        $invoices = $query->get();

        $sorted = match ($this->sortBy) {
            'date' => $invoices->sortBy(fn (Invoice $invoice) => $invoice->invoice_date?->timestamp ?? 0),
            'amount' => $invoices->sortByDesc(fn (Invoice $invoice) => (float) $invoice->total),
            'num' => $invoices->sortBy(fn (Invoice $invoice) => $invoice->invoice_number),
            default => $invoices->sortBy([
                fn (Invoice $invoice) => $invoice->customer?->customer_number ?? '',
                fn (Invoice $invoice) => $invoice->invoice_date?->timestamp ?? 0,
            ]),
        };

        return $sorted
            ->values()
            ->groupBy('customer_id')
            ->map(function (Collection $group) {
                $customer = $group->first()?->customer;
                $open = '0.00';
                $amount = '0.00';
                foreach ($group as $invoice) {
                    $open = bcadd($open, (string) $invoice->balance_due, 2);
                    $amount = bcadd($amount, (string) $invoice->total, 2);
                }

                return [
                    'customer_label' => $customer
                        ? trim(($customer->customer_number ? $customer->customer_number.' ' : '').'('.$customer->display_name.')')
                        : 'Unknown',
                    'invoices' => $group->values(),
                    'open_balance' => $open,
                    'amount' => $amount,
                ];
            })
            ->values();
    }

    public function render()
    {
        $groups = $this->groupedRows();
        $grandOpen = '0.00';
        $grandAmount = '0.00';
        foreach ($groups as $group) {
            $grandOpen = bcadd($grandOpen, $group['open_balance'], 2);
            $grandAmount = bcadd($grandAmount, $group['amount'], 2);
        }

        return view('livewire.reports.customer-open-balance-report', [
            'groups' => $groups,
            'grandOpen' => $grandOpen,
            'grandAmount' => $grandAmount,
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'Customer Open Balance',
            'windowTitle' => 'Customer Open Balance',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Customer Open Balance';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'customer-open-balance.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Type</th><th>Date</th><th>Num</th><th class="num">Open Balance</th><th class="num">Amount</th></tr></thead><tbody>';
        foreach ($this->groupedRows() as $group) {
            $html .= '<tr class="group"><td colspan="5">'.e($group['customer_label']).'</td></tr>';
            foreach ($group['invoices'] as $invoice) {
                $html .= '<tr>'
                    .'<td>Invoice</td>'
                    .'<td>'.e($invoice->invoice_date?->format('m/d/Y') ?? '').'</td>'
                    .'<td>'.e($invoice->invoice_number).'</td>'
                    .'<td class="num">'.e(number_format((float) $invoice->balance_due, 2)).'</td>'
                    .'<td class="num">'.e(number_format((float) $invoice->total, 2)).'</td>'
                    .'</tr>';
            }
        }
        $html .= '</tbody></table>';

        return $html;
    }
}
