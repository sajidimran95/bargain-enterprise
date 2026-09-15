<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\ReceivePaymentAction;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Support\DocumentNumbers;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Receive Payment')]
class PaymentForm extends Component
{
    public string $payment_number = '';

    public string $customer_id = '';

    public string $payment_date = '';

    public string $amount = '';

    public string $method = 'check';

    public string $reference = '';

    public string $deposit_to_account_id = '';

    public string $memo = '';

    /** @var array<int, string> */
    public array $allocations = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('payment.create'), 403);
        $this->payment_number = DocumentNumbers::next(Payment::class, 'payment_number', 'PMT-');
        $this->payment_date = now()->toDateString();
    }

    public function updatedCustomerId(): void
    {
        $this->allocations = [];
        if (! $this->customer_id) {
            return;
        }

        $invoices = Invoice::query()
            ->where('customer_id', $this->customer_id)
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderBy('invoice_date')
            ->get();

        foreach ($invoices as $invoice) {
            $this->allocations[$invoice->id] = '';
        }
    }

    public function save(ReceivePaymentAction $action): mixed
    {
        abort_unless(auth()->user()?->hasPermission('payment.create'), 403);

        $this->validate([
            'payment_number' => ['required', 'string', 'max:50', 'unique:payments,payment_number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:100'],
            'deposit_to_account_id' => ['nullable', 'exists:accounts,id'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $allocationRows = [];
        foreach ($this->allocations as $invoiceId => $amount) {
            if ($amount === '' || $amount === null || (float) $amount <= 0) {
                continue;
            }
            $allocationRows[] = [
                'invoice_id' => (int) $invoiceId,
                'amount' => $amount,
            ];
        }

        try {
            $payment = $action->handle([
                'customer_id' => (int) $this->customer_id,
                'payment_number' => $this->payment_number,
                'payment_date' => $this->payment_date,
                'amount' => $this->amount,
                'method' => $this->method,
                'reference' => $this->reference ?: null,
                'deposit_to_account_id' => $this->deposit_to_account_id ?: null,
                'memo' => $this->memo ?: null,
                'created_by' => auth()->id(),
            ], $allocationRows);
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->dispatch('be-toast', message: 'Payment '.$payment->payment_number.' saved.');

        return $this->redirect(route('payments.index'), navigate: true);
    }

    public function render()
    {
        $openInvoices = $this->customer_id
            ? Invoice::query()
                ->where('customer_id', $this->customer_id)
                ->whereIn('status', ['open', 'partial'])
                ->where('balance_due', '>', 0)
                ->orderBy('invoice_date')
                ->get()
            : collect();

        return view('livewire.sales.payment-form', [
            'customers' => Customer::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'accounts' => Account::query()->where('type', 'asset')->where('is_active', true)->orderBy('number')
                ->get()->mapWithKeys(fn (Account $a) => [$a->id => $a->number.' — '.$a->name])->all(),
            'openInvoices' => $openInvoices,
        ])->layoutData([
            'title' => 'Receive Payment',
            'windowTitle' => 'Receive Payment',
        ]);
    }
}
