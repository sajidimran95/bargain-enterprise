<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\ApplyCreditMemoAction;
use App\Actions\Sales\RefundCreditMemoAction;
use App\Models\Account;
use App\Models\CreditMemo;
use App\Models\Invoice;
use App\Support\PaymentMethods;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Apply / Refund Credit')]
class CreditMemoApply extends Component
{
    public CreditMemo $creditMemo;

    public string $mode = 'apply';

    /** @var array<int, string> */
    public array $allocations = [];

    public string $refund_date = '';

    public string $refund_amount = '';

    public string $refund_method = '';

    public string $refund_account_id = '';

    public string $refund_reference = '';

    public string $refund_memo = '';

    public function mount(CreditMemo $creditMemo): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->creditMemo = $creditMemo->load('customer');
        $this->refund_date = now()->toDateString();
        $this->refund_amount = number_format((float) $creditMemo->remaining_credit, 2, '.', '');
        $this->refund_method = PaymentMethods::defaultCode('check');

        $cash = Account::query()->where('number', '1000')->first();
        $this->refund_account_id = $cash ? (string) $cash->id : '';

        $this->loadOpenInvoices();
    }

    public function loadOpenInvoices(): void
    {
        $this->allocations = [];

        $invoices = Invoice::query()
            ->where('customer_id', $this->creditMemo->customer_id)
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderBy('invoice_date')
            ->get();

        foreach ($invoices as $invoice) {
            $this->allocations[$invoice->id] = '';
        }
    }

    public function applyCredit(ApplyCreditMemoAction $action): mixed
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $rows = [];
        foreach ($this->allocations as $invoiceId => $amount) {
            if ($amount === '' || $amount === null || (float) $amount <= 0) {
                continue;
            }
            $rows[] = [
                'invoice_id' => (int) $invoiceId,
                'amount' => $amount,
            ];
        }

        try {
            $memo = $action->handle($this->creditMemo, $rows);
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

        $this->dispatch('be-toast', message: 'Credit applied to invoice(s).');

        if (! $memo->hasRemainingCredit()) {
            return $this->redirect(route('credit-memos.index'), navigate: true);
        }

        $this->creditMemo = $memo->fresh('customer');
        $this->loadOpenInvoices();
        $this->refund_amount = number_format((float) $this->creditMemo->remaining_credit, 2, '.', '');

        return null;
    }

    public function giveRefund(RefundCreditMemoAction $action): mixed
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'refund_date' => ['required', 'date'],
            'refund_amount' => ['required', 'numeric', 'gt:0'],
            'refund_method' => ['required', 'string', 'max:50'],
            'refund_account_id' => ['required', 'exists:accounts,id'],
            'refund_reference' => ['nullable', 'string', 'max:100'],
            'refund_memo' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $action->handle($this->creditMemo, [
                'refund_date' => $this->refund_date,
                'amount' => $this->refund_amount,
                'method' => $this->refund_method,
                'account_id' => (int) $this->refund_account_id,
                'reference' => $this->refund_reference ?: null,
                'memo' => $this->refund_memo ?: null,
                'created_by' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->dispatch('be-toast', message: 'Refund posted.');

        return $this->redirect(route('credit-memos.index'), navigate: true);
    }

    public function render()
    {
        $invoices = Invoice::query()
            ->where('customer_id', $this->creditMemo->customer_id)
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderBy('invoice_date')
            ->get();

        $payoutAccounts = Account::query()
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereIn('subtype', ['cash', 'bank'])
                    ->orWhereIn('number', ['1000', '1010', '1020']);
            })
            ->orderBy('number')
            ->get()
            ->mapWithKeys(fn (Account $account) => [$account->id => $account->number.' — '.$account->name])
            ->all();

        return view('livewire.sales.credit-memo-apply', [
            'invoices' => $invoices,
            'payoutAccounts' => $payoutAccounts,
            'paymentMethodOptions' => PaymentMethods::options(),
        ])->layoutData([
            'title' => 'Apply / Refund Credit',
            'windowTitle' => 'Apply / Refund Credit',
        ]);
    }
}
