<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\VendorPayment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Vendor Payments')]
class VendorPaymentIndex extends Component
{
    use CreatesErpDrafts;
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);
    }

    public function createDraft(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        try {
            $vendor = $this->requireFirstVendor();
        } catch (RuntimeException $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        VendorPayment::query()->create([
            'payment_number' => $this->nextNumber(VendorPayment::class, 'payment_number', 'VPAY-'),
            'vendor_id' => $vendor->id,
            'payment_date' => now()->toDateString(),
            'amount' => 0,
            'method' => 'check',
            'memo' => 'Draft vendor payment',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft vendor payment created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $rows = VendorPayment::query()
            ->with('vendor')
            ->withCount('allocations')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('payment_number', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (VendorPayment $payment) => [
                $payment->payment_date?->format('Y-m-d'),
                $payment->payment_number,
                $payment->vendor?->display_name,
                $payment->method,
                number_format((float) $payment->amount, 2, '.', ''),
                $payment->allocations_count,
            ]);

        return $this->csvDownload(
            'vendor-payments.csv',
            ['Date', 'Number', 'Vendor', 'Method', 'Amount', 'Allocations'],
            $rows
        );
    }

    public function render()
    {
        $payments = VendorPayment::query()
            ->with('vendor')
            ->withCount('allocations')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('payment_number', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.purchasing.vendor-payment-index', [
            'payments' => $payments,
        ])->layoutData([
            'title' => 'Pay Bills',
            'windowTitle' => 'Vendor Payments',
        ]);
    }
}
