<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Account;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Models\VendorPayment;
use App\Models\VendorPaymentAllocation;
use App\Support\DocumentNumbers;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Pay Bills')]
class VendorPaymentForm extends Component
{
    use WithErpListActions;

    public string $payment_number = '';

    public string $vendor_id = '';

    public string $payment_date = '';

    public string $amount = '';

    public string $method = 'check';

    public string $check_number = '';

    public string $bank_account_id = '';

    public string $memo = '';

    /** @var array<int, string> */
    public array $allocations = [];

    /** @var array<int, bool> */
    public array $selectedBills = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);
        $this->payment_number = DocumentNumbers::next(VendorPayment::class, 'payment_number', 'VPMT-');
        $this->payment_date = now()->toDateString();
    }

    public function updatedVendorId(): void
    {
        $this->allocations = [];
        $this->selectedBills = [];
        $this->amount = '';
        $this->selectedLineId = null;

        if (! $this->vendor_id) {
            return;
        }

        VendorBill::query()
            ->where('vendor_id', $this->vendor_id)
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderBy('bill_date')
            ->get()
            ->each(function (VendorBill $bill) {
                $this->allocations[$bill->id] = '';
                $this->selectedBills[$bill->id] = false;
            });
    }

    public function toggleBill(int $billId): void
    {
        $bill = VendorBill::query()->findOrFail($billId);
        $checked = ! ($this->selectedBills[$billId] ?? false);
        $this->selectedBills[$billId] = $checked;
        $this->allocations[$billId] = $checked
            ? number_format((float) $bill->balance_due, 2, '.', '')
            : '';
        $this->syncAmountFromAllocations();
    }

    public function payAllOpen(): void
    {
        foreach ($this->allocations as $billId => $_) {
            $bill = VendorBill::query()->find($billId);
            if (! $bill) {
                continue;
            }
            $this->selectedBills[$billId] = true;
            $this->allocations[$billId] = number_format((float) $bill->balance_due, 2, '.', '');
        }
        $this->syncAmountFromAllocations();
    }

    public function clearAllocations(): void
    {
        foreach ($this->allocations as $billId => $_) {
            $this->allocations[$billId] = '';
            $this->selectedBills[$billId] = false;
        }
        $this->amount = '';
    }

    public function syncAmountFromAllocations(): void
    {
        $this->amount = $this->allocationsTotal();
        foreach ($this->allocations as $billId => $amount) {
            $this->selectedBills[$billId] = $amount !== '' && (float) $amount > 0;
        }
    }

    public function allocationsTotal(): string
    {
        $total = '0.00';
        foreach ($this->allocations as $amount) {
            if ($amount === '' || (float) $amount <= 0) {
                continue;
            }
            $total = bcadd($total, number_format((float) $amount, 2, '.', ''), 2);
        }

        return $total;
    }

    public function save(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        if ($this->amount === '' || (float) $this->amount <= 0) {
            $this->syncAmountFromAllocations();
        }

        $this->validate([
            'payment_number' => ['required', 'string', 'max:50', 'unique:vendor_payments,payment_number'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'string', 'max:50'],
            'bank_account_id' => ['nullable', 'exists:accounts,id'],
            'memo' => ['nullable', 'string', 'max:255'],
            'check_number' => ['nullable', 'string', 'max:50'],
        ]);

        $applied = $this->allocationsTotal();
        if (bccomp($applied, '0', 2) <= 0) {
            $this->addError('amount', 'Select at least one bill to pay.');

            return null;
        }

        DB::transaction(function () use ($applied) {
            $payment = VendorPayment::query()->create([
                'payment_number' => $this->payment_number,
                'vendor_id' => (int) $this->vendor_id,
                'payment_date' => $this->payment_date,
                'amount' => $applied,
                'method' => $this->method,
                'bank_account_id' => $this->bank_account_id ?: null,
                'memo' => trim(($this->check_number !== '' ? 'Check #'.$this->check_number.' · ' : '').($this->memo ?: '')),
            ]);

            foreach ($this->allocations as $billId => $amount) {
                if ($amount === '' || (float) $amount <= 0) {
                    continue;
                }

                $bill = VendorBill::query()->lockForUpdate()->findOrFail($billId);
                $apply = number_format((float) $amount, 2, '.', '');
                VendorPaymentAllocation::query()->create([
                    'vendor_payment_id' => $payment->id,
                    'vendor_bill_id' => $bill->id,
                    'amount' => $apply,
                ]);

                $bill->amount_paid = bcadd((string) $bill->amount_paid, $apply, 2);
                $bill->balance_due = bcsub((string) $bill->balance_due, $apply, 2);
                $bill->status = bccomp((string) $bill->balance_due, '0', 2) === 0
                    ? 'paid'
                    : 'partial';
                $bill->save();
            }

            $vendor = Vendor::query()->lockForUpdate()->findOrFail((int) $this->vendor_id);
            $vendor->balance = bcsub((string) $vendor->balance, $applied, 2);
            $vendor->save();
        });

        $this->dispatch('be-toast', message: 'Vendor payment '.$this->payment_number.' saved.');

        return $this->redirect(route('vendor-payments.index'), navigate: true);
    }

    public function render()
    {
        $openBills = $this->vendor_id
            ? VendorBill::query()
                ->where('vendor_id', $this->vendor_id)
                ->whereIn('status', ['open', 'partial'])
                ->where('balance_due', '>', 0)
                ->orderBy('bill_date')
                ->get()
            : collect();

        return view('livewire.purchasing.vendor-payment-form', [
            'vendors' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'accounts' => Account::query()->where('type', 'asset')->where('is_active', true)->orderBy('number')
                ->get()->mapWithKeys(fn (Account $a) => [$a->id => $a->number.' — '.$a->name])->all(),
            'openBills' => $openBills,
        ])->layoutData([
            'title' => 'Pay Bills',
            'windowTitle' => 'Pay Bills',
        ]);
    }
}
