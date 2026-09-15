<?php

namespace App\Livewire\Banking;

use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Deposit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Reconcile')]
class ReconciliationWorksheet extends Component
{
    public string $bank_account_id = '';

    public string $statement_date = '';

    public string $statement_ending_balance = '';

    public bool $started = false;

    /** @var array<int|string, bool> */
    public array $clearedDeposits = [];

    /** @var array<int|string, bool> */
    public array $clearedChecks = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);
        $this->statement_date = now()->toDateString();
    }

    public function start(): void
    {
        $this->validate([
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'statement_date' => ['required', 'date'],
            'statement_ending_balance' => ['required', 'numeric'],
        ]);

        $this->started = true;
        $this->clearedDeposits = [];
        $this->clearedChecks = [];

        foreach ($this->unclearedDeposits() as $deposit) {
            $this->clearedDeposits[$deposit->id] = false;
        }
        foreach ($this->unclearedChecks() as $check) {
            $this->clearedChecks[$check->id] = false;
        }
    }

    public function resetWorksheet(): void
    {
        $this->started = false;
        $this->clearedDeposits = [];
        $this->clearedChecks = [];
        $this->resetErrorBag();
    }

    public function finish(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);

        if (! $this->started) {
            return;
        }

        if (bccomp($this->difference(), '0', 2) !== 0) {
            $this->addError('finish', 'Difference must be 0.00 before finishing reconciliation.');

            return;
        }

        $now = now();

        foreach ($this->clearedDeposits as $id => $isCleared) {
            if ($isCleared) {
                Deposit::query()->whereKey((int) $id)->whereNull('cleared_at')->update(['cleared_at' => $now]);
            }
        }

        foreach ($this->clearedChecks as $id => $isCleared) {
            if ($isCleared) {
                Check::query()->whereKey((int) $id)->whereNull('cleared_at')->update(['cleared_at' => $now]);
            }
        }

        $this->dispatch('be-toast', message: 'Reconciliation finished. Cleared items locked.');
        $this->resetWorksheet();
        $this->statement_ending_balance = '';
    }

    protected function unclearedDeposits()
    {
        return Deposit::query()
            ->where('bank_account_id', (int) $this->bank_account_id)
            ->whereNull('cleared_at')
            ->whereDate('deposit_date', '<=', $this->statement_date)
            ->orderBy('deposit_date')
            ->get();
    }

    protected function unclearedChecks()
    {
        return Check::query()
            ->where('bank_account_id', (int) $this->bank_account_id)
            ->whereNull('cleared_at')
            ->whereDate('check_date', '<=', $this->statement_date)
            ->orderBy('check_date')
            ->get();
    }

    public function clearedDepositsTotal(): string
    {
        $total = '0.00';
        foreach ($this->unclearedDeposits() as $deposit) {
            if ($this->clearedDeposits[$deposit->id] ?? false) {
                $total = bcadd($total, (string) $deposit->total, 2);
            }
        }

        return $total;
    }

    public function clearedChecksTotal(): string
    {
        $total = '0.00';
        foreach ($this->unclearedChecks() as $check) {
            if ($this->clearedChecks[$check->id] ?? false) {
                $total = bcadd($total, (string) $check->amount, 2);
            }
        }

        return $total;
    }

    public function beginningBalance(): string
    {
        $account = BankAccount::query()->find((int) $this->bank_account_id);
        if (! $account) {
            return '0.00';
        }

        $opening = number_format((float) $account->opening_balance, 2, '.', '');
        $priorDeposits = Deposit::query()
            ->where('bank_account_id', $account->id)
            ->whereNotNull('cleared_at')
            ->sum('total');
        $priorChecks = Check::query()
            ->where('bank_account_id', $account->id)
            ->whereNotNull('cleared_at')
            ->sum('amount');

        return bcsub(bcadd($opening, (string) $priorDeposits, 2), (string) $priorChecks, 2);
    }

    public function clearedBalance(): string
    {
        return bcsub(
            bcadd($this->beginningBalance(), $this->clearedDepositsTotal(), 2),
            $this->clearedChecksTotal(),
            2
        );
    }

    public function difference(): string
    {
        $ending = number_format((float) ($this->statement_ending_balance ?: 0), 2, '.', '');

        return bcsub($ending, $this->clearedBalance(), 2);
    }

    public function render()
    {
        return view('livewire.banking.reconciliation-worksheet', [
            'bankAccounts' => BankAccount::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all(),
            'deposits' => $this->started ? $this->unclearedDeposits() : collect(),
            'checks' => $this->started ? $this->unclearedChecks() : collect(),
            'beginningBalance' => $this->started ? $this->beginningBalance() : '0.00',
            'clearedDepositsTotal' => $this->started ? $this->clearedDepositsTotal() : '0.00',
            'clearedChecksTotal' => $this->started ? $this->clearedChecksTotal() : '0.00',
            'clearedBalance' => $this->started ? $this->clearedBalance() : '0.00',
            'difference' => $this->started ? $this->difference() : '0.00',
        ])->layoutData([
            'title' => 'Reconcile',
            'windowTitle' => 'Reconcile',
        ]);
    }
}
