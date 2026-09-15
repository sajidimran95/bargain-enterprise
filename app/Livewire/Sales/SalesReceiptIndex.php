<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\SalesReceipt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Sales Receipts')]
class SalesReceiptIndex extends Component
{
    use CreatesErpDrafts;
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);
    }

    public function createDraft(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        try {
            $customer = $this->requireFirstCustomer();
        } catch (RuntimeException $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        SalesReceipt::query()->create([
            'number' => $this->nextNumber(SalesReceipt::class, 'number', 'SR-'),
            'customer_id' => $customer->id,
            'receipt_date' => now()->toDateString(),
            'subtotal' => 0,
            'tax_total' => 0,
            'total' => 0,
            'payment_method' => 'cash',
            'memo' => 'Draft sales receipt',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft sales receipt created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $rows = SalesReceipt::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('receipt_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (SalesReceipt $receipt) => [
                $receipt->receipt_date?->format('Y-m-d'),
                $receipt->number,
                $receipt->customer?->display_name,
                $receipt->payment_method,
                $receipt->lines_count,
                number_format((float) $receipt->total, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'sales-receipts.csv',
            ['Date', 'Number', 'Customer', 'Method', 'Lines', 'Total'],
            $rows
        );
    }

    public function render()
    {
        $receipts = SalesReceipt::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('receipt_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.sales.sales-receipt-index', [
            'receipts' => $receipts,
        ])->layoutData([
            'title' => 'Sales Receipts',
            'windowTitle' => 'Sales Receipts',
        ]);
    }
}
