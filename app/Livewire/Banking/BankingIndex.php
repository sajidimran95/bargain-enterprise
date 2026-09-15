<?php

namespace App\Livewire\Banking;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Account;
use App\Models\BankAccount;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Banking')]
class BankingIndex extends Component
{
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.view'), 403);
    }

    public function createBankAccount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);

        $nextGl = (int) Account::query()->where('type', 'asset')->max('number') + 10;
        $glNumber = (string) $nextGl;

        $account = Account::query()->create([
            'number' => $glNumber,
            'name' => 'Bank Account '.$glNumber,
            'type' => 'asset',
            'subtype' => 'bank',
            'is_active' => true,
            'is_system' => false,
        ]);

        BankAccount::query()->create([
            'account_id' => $account->id,
            'name' => $account->name,
            'bank_name' => 'New Bank',
            'opening_balance' => 0,
            'is_active' => true,
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Bank account created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('banking.view'), 403);

        $rows = BankAccount::query()
            ->with('account')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('name', 'like', $like)
                        ->orWhere('bank_name', 'like', $like)
                        ->orWhereHas('account', fn ($a) => $a->where('number', 'like', $like)->orWhere('name', 'like', $like));
                });
            })
            ->orderBy('name')
            ->get()
            ->map(fn (BankAccount $bank) => [
                $bank->name,
                $bank->bank_name,
                $bank->account?->number,
                $bank->account?->name,
                $bank->account_number_mask,
                number_format((float) $bank->opening_balance, 2, '.', ''),
                $bank->is_active ? 'Yes' : 'No',
            ]);

        return $this->csvDownload(
            'bank-accounts.csv',
            ['Name', 'Bank', 'GL Number', 'GL Name', 'Account Mask', 'Opening Balance', 'Active'],
            $rows
        );
    }

    public function render()
    {
        $accounts = BankAccount::query()
            ->with('account')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('name', 'like', $like)
                        ->orWhere('bank_name', 'like', $like)
                        ->orWhereHas('account', fn ($a) => $a->where('number', 'like', $like)->orWhere('name', 'like', $like));
                });
            })
            ->orderBy('name')
            ->paginate(30);

        return view('livewire.banking.banking-index', [
            'accounts' => $accounts,
        ])->layoutData([
            'title' => 'Banking',
            'windowTitle' => 'Bank Accounts',
        ]);
    }
}
