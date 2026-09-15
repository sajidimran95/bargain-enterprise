<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\SalesOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Sales Orders')]
class SalesOrderIndex extends Component
{
    use CreatesErpDrafts;
    use WithErpListActions;
    use WithPagination;

    #[Url]
    public string $status = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
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

        SalesOrder::query()->create([
            'number' => $this->nextNumber(SalesOrder::class, 'number', 'SO-'),
            'customer_id' => $customer->id,
            'order_date' => now()->toDateString(),
            'status' => 'draft',
            'subtotal' => 0,
            'tax_total' => 0,
            'total' => 0,
            'memo' => 'Draft sales order',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft sales order created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $rows = SalesOrder::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (SalesOrder $order) => [
                $order->order_date?->format('Y-m-d'),
                $order->number,
                $order->customer?->display_name,
                $order->status,
                $order->lines_count,
                number_format((float) $order->total, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'sales-orders.csv',
            ['Date', 'Number', 'Customer', 'Status', 'Lines', 'Total'],
            $rows
        );
    }

    public function render()
    {
        $orders = SalesOrder::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.sales.sales-order-index', [
            'orders' => $orders,
        ])->layoutData([
            'title' => 'Sales Orders',
            'windowTitle' => 'Sales Orders',
        ]);
    }
}
