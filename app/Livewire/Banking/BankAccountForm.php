<?php

namespace App\Livewire\Banking;

use App\Models\Account;
use App\Models\BankAccount;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('New Bank Account')]
class BankAccountForm extends Component
{
    public string $name = '';

    public string $bank_name = '';

    public string $account_number_mask = '';

    public string $opening_balance = '0.00';

    public string $gl_number = '';

    public string $gl_name = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);
        $next = ((int) Account::query()->where('type', 'asset')->max('number')) + 10;
        $this->gl_number = (string) $next;
        $this->gl_name = 'Bank Account '.$this->gl_number;
        $this->name = $this->gl_name;
    }

    public function save(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'bank_name' => ['required', 'string', 'max:120'],
            'account_number_mask' => ['nullable', 'string', 'max:40'],
            'opening_balance' => ['required', 'numeric'],
            'gl_number' => ['required', 'string', 'max:20', 'unique:accounts,number'],
            'gl_name' => ['required', 'string', 'max:120'],
        ]);

        $account = Account::query()->create([
            'number' => $validated['gl_number'],
            'name' => $validated['gl_name'],
            'type' => 'asset',
            'subtype' => 'bank',
            'is_active' => true,
            'is_system' => false,
        ]);

        BankAccount::query()->create([
            'account_id' => $account->id,
            'name' => $validated['name'],
            'bank_name' => $validated['bank_name'],
            'account_number_mask' => $validated['account_number_mask'] ?: null,
            'opening_balance' => $validated['opening_balance'],
            'is_active' => true,
        ]);

        $this->dispatch('be-toast', message: 'Bank account created.');

        return $this->redirect(route('banking.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.banking.bank-account-form')->layoutData([
            'title' => 'New Bank Account',
            'windowTitle' => 'New Bank Account',
        ]);
    }
}
