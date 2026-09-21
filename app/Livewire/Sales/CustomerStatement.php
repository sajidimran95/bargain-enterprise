<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\WithReportDelivery;
use App\Models\CreditMemo;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Support\XlsxExporter;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Create Statements')]
class CustomerStatement extends Component
{
    use WithReportDelivery;

    #[Url]
    public string $customer_id = '';

    public string $statement_date = '';

    public string $from = '';

    public string $to = '';

    public string $datePreset = 'this_month';

    public string $sortBy = 'default';

    public bool $hideHeader = false;

    public bool $showExtraFilters = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);
        $this->statement_date = now()->toDateString();
        $this->applyDatePreset('this_month');
    }

    public function updatedDatePreset(string $value): void
    {
        $this->applyDatePreset($value);
    }

    public function applyDatePreset(?string $preset = null): void
    {
        $preset ??= $this->datePreset;
        $this->datePreset = $preset;

        [$from, $to] = match ($preset) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'this_week' => [now()->startOfWeek(), now()->endOfWeek()],
            'this_month' => [now()->startOfMonth(), now()->endOfMonth()],
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'all' => [Carbon::parse('2000-01-01')->startOfDay(), now()->endOfDay()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };

        $this->from = $from->toDateString();
        $this->to = $to->toDateString();
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export') || auth()->user()?->hasPermission('invoice.view'), 403);

        $rows = $this->lines()->map(fn (array $row) => [
            $row['date'],
            $row['type'],
            $row['num'],
            $row['memo'],
            number_format($row['amount'], 2, '.', ''),
            number_format($row['balance'], 2, '.', ''),
        ]);

        return app(XlsxExporter::class)->download(
            'customer-statement.xlsm',
            ['Date', 'Type', 'Num', 'Memo', 'Amount', 'Balance'],
            $rows,
            title: 'Customer Statement',
        );
    }

    /**
     * Opening balance before $from.
     */
    protected function openingBalance(int $customerId): float
    {
        $invoiced = (float) Invoice::query()
            ->where('customer_id', $customerId)
            ->whereNot('status', 'draft')
            ->whereDate('invoice_date', '<', $this->from)
            ->sum('total');

        $paid = (float) Payment::query()
            ->where('customer_id', $customerId)
            ->whereDate('payment_date', '<', $this->from)
            ->sum('amount');

        $credits = (float) CreditMemo::query()
            ->where('customer_id', $customerId)
            ->whereNot('status', 'draft')
            ->whereDate('credit_date', '<', $this->from)
            ->sum('total');

        return round($invoiced - $paid - $credits, 2);
    }

    /**
     * @return Collection<int, array{date: string, type: string, num: string, memo: string, amount: float, balance: float}>
     */
    protected function lines(): Collection
    {
        if ($this->customer_id === '') {
            return collect();
        }

        $customerId = (int) $this->customer_id;
        $balance = $this->openingBalance($customerId);
        $rows = collect();

        $rows->push([
            'date' => Carbon::parse($this->from)->format('m/d/Y'),
            'type' => 'Opening Balance',
            'num' => '',
            'memo' => 'Balance forward',
            'amount' => $balance,
            'balance' => $balance,
            '_sort' => Carbon::parse($this->from)->startOfDay()->timestamp - 1,
        ]);

        foreach (Invoice::query()
            ->where('customer_id', $customerId)
            ->whereNot('status', 'draft')
            ->whereDate('invoice_date', '>=', $this->from)
            ->whereDate('invoice_date', '<=', $this->to)
            ->orderBy('invoice_date')
            ->get() as $invoice) {
            $amount = (float) $invoice->total;
            $balance = round($balance + $amount, 2);
            $rows->push([
                'date' => $invoice->invoice_date?->format('m/d/Y') ?? '',
                'type' => 'Invoice',
                'num' => $invoice->invoice_number,
                'memo' => $invoice->memo ?? '',
                'amount' => $amount,
                'balance' => $balance,
                '_sort' => $invoice->invoice_date?->timestamp ?? 0,
            ]);
        }

        foreach (Payment::query()
            ->where('customer_id', $customerId)
            ->whereDate('payment_date', '>=', $this->from)
            ->whereDate('payment_date', '<=', $this->to)
            ->orderBy('payment_date')
            ->get() as $payment) {
            $amount = -1 * (float) $payment->amount;
            $balance = round($balance + $amount, 2);
            $rows->push([
                'date' => $payment->payment_date?->format('m/d/Y') ?? '',
                'type' => 'Payment',
                'num' => $payment->payment_number,
                'memo' => $payment->memo ?? ($payment->method ?? ''),
                'amount' => $amount,
                'balance' => $balance,
                '_sort' => $payment->payment_date?->timestamp ?? 0,
            ]);
        }

        foreach (CreditMemo::query()
            ->where('customer_id', $customerId)
            ->whereNot('status', 'draft')
            ->whereDate('credit_date', '>=', $this->from)
            ->whereDate('credit_date', '<=', $this->to)
            ->orderBy('credit_date')
            ->get() as $memo) {
            $amount = -1 * (float) $memo->total;
            $balance = round($balance + $amount, 2);
            $rows->push([
                'date' => $memo->credit_date?->format('m/d/Y') ?? '',
                'type' => 'Credit',
                'num' => $memo->credit_number,
                'memo' => $memo->memo ?? '',
                'amount' => $amount,
                'balance' => $balance,
                '_sort' => $memo->credit_date?->timestamp ?? 0,
            ]);
        }

        return $rows->sortBy('_sort')->values()->map(function (array $row) {
            unset($row['_sort']);

            return $row;
        });
    }

    public function render()
    {
        $customer = $this->customer_id !== ''
            ? Customer::query()->find($this->customer_id)
            : null;

        $lines = $this->lines();
        $ending = $lines->last()['balance'] ?? 0.0;

        return view('livewire.sales.customer-statement', [
            'customers' => Customer::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'customer' => $customer,
            'lines' => $lines,
            'endingBalance' => $ending,
            'datePresetOptions' => [
                'today' => 'Today',
                'this_week' => 'This Week',
                'this_month' => 'This Month',
                'last_month' => 'Last Month',
                'this_year' => 'This Year',
                'all' => 'All Dates',
                'custom' => 'Custom',
            ],
            'sortByOptions' => ['default' => 'Default'],
            'subtitle' => $customer
                ? ($customer->display_name.' · '.$this->from.' to '.$this->to)
                : 'Select a customer',
        ])->layoutData([
            'title' => 'Create Statements',
            'windowTitle' => 'Create Statements',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Customer Statement';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->customer_id !== ''
            ? (Customer::query()->find($this->customer_id)?->display_name.' · '.$this->from.' to '.$this->to)
            : null;
    }

    protected function reportPdfFilename(): string
    {
        return 'customer-statement.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Date</th><th>Type</th><th>Num</th><th class="num">Amount</th><th class="num">Balance</th></tr></thead><tbody>';
        foreach ($this->lines() as $row) {
            $html .= '<tr>'
                .'<td>'.e($row['date']).'</td>'
                .'<td>'.e($row['type']).'</td>'
                .'<td>'.e($row['num']).'</td>'
                .'<td class="num">'.e(number_format($row['amount'], 2)).'</td>'
                .'<td class="num">'.e(number_format($row['balance'], 2)).'</td>'
                .'</tr>';
        }

        return $html.'</tbody></table>';
    }
}
