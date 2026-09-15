<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Payments')]
class PaymentIndex extends Component
{
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('payment.view'), 403);
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('payment.view'), 403);

        $rows = Payment::query()
            ->with(['customer', 'allocations.invoice'])
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('payment_number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Payment $payment) => [
                $payment->payment_date?->format('Y-m-d'),
                $payment->payment_number,
                $payment->customer?->display_name,
                $payment->method,
                number_format((float) $payment->amount, 2, '.', ''),
                $payment->allocations->map(fn ($a) => $a->invoice?->invoice_number)->filter()->implode(', '),
                $payment->deposited ? 'Yes' : 'No',
            ]);

        return $this->csvDownload(
            'payments.csv',
            ['Date', 'Number', 'Customer', 'Method', 'Amount', 'Applied To', 'Deposited'],
            $rows
        );
    }

    public function render()
    {
        $payments = Payment::query()
            ->with(['customer', 'allocations.invoice'])
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('payment_number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.sales.payment-index', [
            'payments' => $payments,
        ])->layoutData([
            'title' => 'Receive Payments',
            'windowTitle' => 'Payments',
        ]);
    }
}
