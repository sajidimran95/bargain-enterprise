<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\PurchaseOrder;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Purchase Orders')]
class PurchaseOrderIndex extends Component
{
    use CreatesErpDrafts;
    use WithErpListActions;
    use WithPagination;

    #[Url]
    public string $status = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function openEdit(int $id): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $order = PurchaseOrder::query()->findOrFail($id);
        if (in_array($order->status, ['partial', 'received', 'cancelled'], true)) {
            $this->dispatch('be-toast', message: 'Partially received, fully received, or cancelled purchase orders cannot be edited.');

            return;
        }

        $this->selectedLineId = $id;
        $this->openWorkspaceEdit('purchase-orders.edit', ['purchaseOrder' => $order->id], 'PO: '.$order->number);
    }

    protected function selectedDocumentPdfUrl(int $id): ?string
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $order = PurchaseOrder::query()->findOrFail($id);

        return route('purchase-orders.pdf', $order);
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

        PurchaseOrder::query()->create([
            'number' => $this->nextNumber(PurchaseOrder::class, 'number', 'PO-'),
            'vendor_id' => $vendor->id,
            'order_date' => now()->toDateString(),
            'status' => 'draft',
            'subtotal' => 0,
            'total' => 0,
            'memo' => 'Draft purchase order',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft purchase order created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $rows = PurchaseOrder::query()
            ->with('vendor')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (PurchaseOrder $po) => [
                $po->order_date?->format('Y-m-d'),
                $po->number,
                $po->vendor?->display_name,
                $po->status,
                $po->lines_count,
                number_format((float) $po->total, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'purchase-orders.csv',
            ['Date', 'Number', 'Vendor', 'Status', 'Lines', 'Total'],
            $rows
        );
    }

    public function render()
    {
        $orders = PurchaseOrder::query()
            ->with('vendor')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('order_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.purchasing.purchase-order-index', [
            'orders' => $orders,
        ])->layoutData([
            'title' => 'Purchase Orders',
            'windowTitle' => 'Purchase Orders',
        ]);
    }
}
