<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\CreditMemo;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Credit Memos')]
class CreditMemoIndex extends Component
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

        CreditMemo::query()->create([
            'credit_number' => $this->nextNumber(CreditMemo::class, 'credit_number', 'CM-'),
            'customer_id' => $customer->id,
            'credit_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 0,
            'tax_total' => 0,
            'total' => 0,
            'remaining_credit' => 0,
            'memo' => 'Draft credit memo',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft credit memo created.');
    }

    protected function selectedDocumentPdfUrl(int $id): ?string
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $memo = CreditMemo::query()->findOrFail($id);

        return route('credit-memos.print', $memo);
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $rows = CreditMemo::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('credit_number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('credit_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (CreditMemo $memo) => [
                $memo->credit_date?->format('Y-m-d'),
                $memo->credit_number,
                $memo->customer?->display_name,
                $memo->status,
                $memo->lines_count,
                number_format((float) $memo->total, 2, '.', ''),
                number_format((float) $memo->remaining_credit, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'credit-memos.csv',
            ['Date', 'Number', 'Customer', 'Status', 'Lines', 'Total', 'Remaining'],
            $rows
        );
    }

    public function render()
    {
        $memos = CreditMemo::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('credit_number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('credit_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.sales.credit-memo-index', [
            'memos' => $memos,
        ])->layoutData([
            'title' => 'Credit Memos',
            'windowTitle' => 'Credit Memos',
        ]);
    }
}
