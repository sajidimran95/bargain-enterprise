<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Deposit;
use App\Models\Payment;
use App\Models\VendorPayment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Statement of Cash Flows')]
class CashFlowReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'this_month';
        $this->applyDatePreset('this_month');
    }

    /**
     * @return array{receipts: float, payments: float, deposits: float, net: float}
     */
    protected function statement(): array
    {
        $receipts = (float) Payment::query()
            ->whereDate('payment_date', '>=', $this->from)
            ->whereDate('payment_date', '<=', $this->to)
            ->sum('amount');

        $payments = (float) VendorPayment::query()
            ->whereDate('payment_date', '>=', $this->from)
            ->whereDate('payment_date', '<=', $this->to)
            ->sum('amount');

        $deposits = (float) Deposit::query()
            ->whereDate('deposit_date', '>=', $this->from)
            ->whereDate('deposit_date', '<=', $this->to)
            ->sum('total');

        return [
            'receipts' => $receipts,
            'payments' => $payments,
            'deposits' => $deposits,
            'net' => $receipts - $payments,
        ];
    }

    public function exportExcel(): StreamedResponse
    {
        $s = $this->statement();

        return $this->exportReportCsv(
            'cash-flow.csv',
            ['Line', 'Amount'],
            [
                ['Customer receipts', number_format($s['receipts'], 2, '.', '')],
                ['Vendor payments', number_format($s['payments'], 2, '.', '')],
                ['Bank deposits', number_format($s['deposits'], 2, '.', '')],
                ['Net cash from operations', number_format($s['net'], 2, '.', '')],
            ]
        );
    }

    public function render()
    {
        return view('livewire.reports.cash-flow-report', [
            'statement' => $this->statement(),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'Statement of Cash Flows',
            'windowTitle' => 'Statement of Cash Flows',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Statement of Cash Flows';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'cash-flow.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $s = $this->statement();

        return '<table><tbody>'
            .'<tr><td>Customer receipts</td><td class="num">'.e(number_format($s['receipts'], 2)).'</td></tr>'
            .'<tr><td>Vendor payments</td><td class="num">'.e(number_format($s['payments'], 2)).'</td></tr>'
            .'<tr><td>Net</td><td class="num">'.e(number_format($s['net'], 2)).'</td></tr>'
            .'</tbody></table>';
    }
}
