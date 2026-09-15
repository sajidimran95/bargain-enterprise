<?php

namespace App\Livewire\Banking;

use App\Actions\Banking\CreateCheckAction;
use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Vendor;
use App\Support\DocumentNumbers;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Write Check')]
class CheckForm extends Component
{
    public string $check_number = '';

    public string $bank_account_id = '';

    public string $vendor_id = '';

    public string $check_date = '';

    public string $amount = '';

    public string $payee = '';

    public string $memo = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);
        $this->check_number = DocumentNumbers::next(Check::class, 'check_number', 'CHK-');
        $this->check_date = now()->toDateString();
    }

    public function updatedVendorId(): void
    {
        if ($this->vendor_id === '') {
            return;
        }

        $vendor = Vendor::query()->find($this->vendor_id);
        if ($vendor && $this->payee === '') {
            $this->payee = $vendor->display_name;
        }
    }

    public function save(CreateCheckAction $action): mixed
    {
        abort_unless(auth()->user()?->hasPermission('banking.manage'), 403);

        $this->validate([
            'check_number' => ['required', 'string', 'max:50', 'unique:checks,check_number'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'check_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'payee' => ['required', 'string', 'max:120'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $check = $action->handle([
                'check_number' => $this->check_number,
                'bank_account_id' => (int) $this->bank_account_id,
                'vendor_id' => $this->vendor_id ?: null,
                'check_date' => $this->check_date,
                'amount' => $this->amount,
                'payee' => $this->payee,
                'memo' => $this->memo ?: null,
                'created_by' => auth()->id(),
            ]);
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

        $this->dispatch('be-toast', message: 'Check '.$check->check_number.' posted.');

        return $this->redirect(route('checks.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.banking.simple-money-form', [
            'pageTitle' => 'Write Check',
            'cancelRoute' => 'checks.index',
            'numberLabel' => 'Check #',
            'numberField' => 'check_number',
            'amountField' => 'amount',
            'bankAccounts' => BankAccount::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id')->all(),
            'vendors' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'showPayee' => true,
        ])->layoutData([
            'title' => 'Write Check',
            'windowTitle' => 'Write Check',
        ]);
    }
}
