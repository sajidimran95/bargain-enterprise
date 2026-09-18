<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\VendorBill;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Vendor Bills')]
class VendorBillIndex extends Component
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

        $bill = VendorBill::query()->findOrFail($id);
        $this->selectedLineId = $id;

        $isRtv = str_starts_with((string) $bill->bill_number, 'RTV-')
            || str_contains((string) ($bill->memo ?? ''), 'CREDIT');

        if ($isRtv) {
            $this->openWorkspaceEdit('vendor-returns.edit', ['vendorBill' => $bill->id], 'RTV: '.$bill->bill_number);

            return;
        }

        $this->openWorkspaceEdit('vendor-bills.edit', ['vendorBill' => $bill->id], 'Bill: '.$bill->bill_number);
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

        VendorBill::query()->create([
            'bill_number' => $this->nextNumber(VendorBill::class, 'bill_number', 'BILL-'),
            'vendor_id' => $vendor->id,
            'bill_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'open',
            'subtotal' => 0,
            'total' => 0,
            'amount_paid' => 0,
            'balance_due' => 0,
            'memo' => 'Draft vendor bill',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft vendor bill created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $rows = VendorBill::query()
            ->with('vendor')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('bill_number', 'like', $like)
                        ->orWhere('ref_no', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('bill_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (VendorBill $bill) => [
                $bill->bill_date?->format('Y-m-d'),
                $bill->bill_number,
                $bill->vendor?->display_name,
                $bill->status,
                $bill->lines_count,
                number_format((float) $bill->total, 2, '.', ''),
                number_format((float) $bill->balance_due, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'vendor-bills.csv',
            ['Date', 'Bill #', 'Vendor', 'Status', 'Lines', 'Total', 'Balance'],
            $rows
        );
    }

    public function render()
    {
        $bills = VendorBill::query()
            ->with('vendor')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('bill_number', 'like', $like)
                        ->orWhere('ref_no', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('bill_date')
            ->orderByDesc('id')
            ->paginate(30);

        $openAp = VendorBill::query()->whereIn('status', ['open', 'partial'])->sum('balance_due');

        return view('livewire.purchasing.vendor-bill-index', [
            'bills' => $bills,
            'openAp' => $openAp,
        ])->layoutData([
            'title' => 'Enter Bills',
            'windowTitle' => 'Vendor Bills',
        ]);
    }
}
