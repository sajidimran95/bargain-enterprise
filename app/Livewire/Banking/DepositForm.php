<?php

namespace App\Livewire\Banking;

use App\Actions\Banking\CreateDepositAction;
use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\Payment;
use App\Support\DocumentNumbers;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Make Deposit')]
class DepositForm extends Component
{
    public string $number = '';

    public string $bank_account_id = '';

    public string $deposit_date = '';

    public string $memo = '';

    /** @var array<int, bool> */
    public array $selectedPayments = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);
        $this->number = DocumentNumbers::next(Deposit::class, 'number', 'DEP-');
        $this->deposit_date = now()->toDateString();
    }

    public function getSelectedTotalProperty(): string
    {
        $ids = collect($this->selectedPayments)
            ->filter()
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($ids === []) {
            return '0.00';
        }

        $sum = Payment::query()->whereIn('id', $ids)->sum('amount');

        return number_format((float) $sum, 2, '.', '');
    }

    public function save(CreateDepositAction $action): mixed
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);

        $this->validate([
            'number' => ['required', 'string', 'max:50', 'unique:deposits,number'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'deposit_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $paymentIds = collect($this->selectedPayments)
            ->filter()
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();

        try {
            $deposit = $action->handle([
                'number' => $this->number,
                'bank_account_id' => (int) $this->bank_account_id,
                'deposit_date' => $this->deposit_date,
                'memo' => $this->memo ?: null,
                'created_by' => auth()->id(),
            ], $paymentIds);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            return null;
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->dispatch('be-toast', message: 'Deposit '.$deposit->number.' posted to bank.');

        return $this->redirect(route('deposits.index'), navigate: true);
    }

    public function render()
    {
        $payments = Payment::query()
            ->with('customer')
            ->where('deposited', false)
            ->orderBy('payment_date')
            ->orderBy('id')
            ->get();

        return view('livewire.banking.deposit-form', [
            'bankAccounts' => BankAccount::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all(),
            'payments' => $payments,
            'selectedTotal' => $this->selectedTotal,
        ])->layoutData([
            'title' => 'Make Deposit',
            'windowTitle' => 'Make Deposit',
        ]);
    }
}
