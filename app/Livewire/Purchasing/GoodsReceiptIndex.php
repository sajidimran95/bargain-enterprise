<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\GoodsReceipt;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Goods Receipts')]
class GoodsReceiptIndex extends Component
{
    use CreatesErpDrafts;
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);
    }

    public function openEdit(int $id): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $receipt = GoodsReceipt::query()->findOrFail($id);
        $this->selectedLineId = $id;
        $this->openWorkspaceEdit('goods-receipts.edit', ['goodsReceipt' => $receipt->id], 'Receipt: '.$receipt->number);
    }

    protected function selectedDocumentPdfUrl(int $id): ?string
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $receipt = GoodsReceipt::query()->findOrFail($id);

        return route('goods-receipts.print', $receipt);
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

        GoodsReceipt::query()->create([
            'number' => $this->nextNumber(GoodsReceipt::class, 'number', 'GR-'),
            'vendor_id' => $vendor->id,
            'receipt_date' => now()->toDateString(),
            'memo' => 'Draft goods receipt',
            'created_by' => auth()->id(),
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft goods receipt created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $rows = GoodsReceipt::query()
            ->with('vendor')
            ->withCount('lines')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhere('memo', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('receipt_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (GoodsReceipt $receipt) => [
                $receipt->receipt_date?->format('Y-m-d'),
                $receipt->number,
                $receipt->vendor?->display_name,
                $receipt->lines_count,
                $receipt->memo,
            ]);

        return $this->csvDownload(
            'goods-receipts.csv',
            ['Date', 'Number', 'Vendor', 'Lines', 'Memo'],
            $rows
        );
    }

    public function render()
    {
        $receipts = GoodsReceipt::query()
            ->with('vendor')
            ->withCount('lines')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhere('memo', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('receipt_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.purchasing.goods-receipt-index', [
            'receipts' => $receipts,
        ])->layoutData([
            'title' => 'Receive Inventory',
            'windowTitle' => 'Goods Receipts',
        ]);
    }
}
