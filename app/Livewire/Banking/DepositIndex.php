<?php

namespace App\Livewire\Banking;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\BankAccount;
use App\Models\Deposit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Deposits')]
class DepositIndex extends Component
{
    use CreatesErpDrafts;
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.view'), 403);
    }

    public function createDraft(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);

        $bankAccount = BankAccount::query()->where('is_active', true)->orderBy('id')->first();

        if (! $bankAccount) {
            $this->dispatch('be-toast', message: 'Create a bank account first.');

            return;
        }

        Deposit::query()->create([
            'number' => $this->nextNumber(Deposit::class, 'number', 'DEP-'),
            'bank_account_id' => $bankAccount->id,
            'deposit_date' => now()->toDateString(),
            'total' => 0,
            'memo' => 'Draft deposit',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft deposit created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('banking.view'), 403);

        $rows = Deposit::query()
            ->with('bankAccount')
            ->withCount('items')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhere('memo', 'like', $like)
                        ->orWhereHas('bankAccount', fn ($b) => $b->where('name', 'like', $like));
                });
            })
            ->orderByDesc('deposit_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Deposit $deposit) => [
                $deposit->deposit_date?->format('Y-m-d'),
                $deposit->number,
                $deposit->bankAccount?->name,
                $deposit->items_count,
                number_format((float) $deposit->total, 2, '.', ''),
                $deposit->memo,
            ]);

        return $this->csvDownload(
            'deposits.csv',
            ['Date', 'Number', 'Bank Account', 'Items', 'Total', 'Memo'],
            $rows
        );
    }

    public function render()
    {
        $deposits = Deposit::query()
            ->with('bankAccount')
            ->withCount('items')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhere('memo', 'like', $like)
                        ->orWhereHas('bankAccount', fn ($b) => $b->where('name', 'like', $like));
                });
            })
            ->orderByDesc('deposit_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.banking.deposit-index', [
            'deposits' => $deposits,
        ])->layoutData([
            'title' => 'Record Deposits',
            'windowTitle' => 'Deposits',
        ]);
    }
}
