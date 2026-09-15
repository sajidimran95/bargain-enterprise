<?php

namespace App\Actions\Sales;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Services\AccountingService;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class ReceivePaymentAction
{
    public function __construct(
        protected AccountingService $accounting,
        protected AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  array<int, array{invoice_id: int, amount: float|string}>  $allocations
     */
    public function handle(array $header, array $allocations): Payment
    {
        $header = Validator::make($header, [
            'customer_id' => ['required', 'exists:customers,id'],
            'payment_number' => ['required', 'string', 'unique:payments,payment_number'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['nullable', 'string'],
            'reference' => ['nullable', 'string'],
            'deposit_to_account_id' => ['nullable', 'exists:accounts,id'],
            'memo' => ['nullable', 'string'],
            'created_by' => ['nullable', 'exists:users,id'],
            'post_accounting' => ['sometimes', 'boolean'],
        ])->validate();

        return DB::transaction(function () use ($header, $allocations) {
            $paymentAmount = number_format((float) $header['amount'], 2, '.', '');
            $allocated = '0.00';

            $payment = Payment::query()->create([
                'payment_number' => $header['payment_number'],
                'customer_id' => $header['customer_id'],
                'payment_date' => $header['payment_date'],
                'amount' => $paymentAmount,
                'unapplied_amount' => $paymentAmount,
                'method' => $header['method'] ?? 'check',
                'reference' => $header['reference'] ?? null,
                'deposit_to_account_id' => $header['deposit_to_account_id'] ?? null,
                'deposited' => false,
                'memo' => $header['memo'] ?? null,
                'created_by' => $header['created_by'] ?? auth()->id(),
            ]);

            foreach ($allocations as $row) {
                $amount = number_format((float) $row['amount'], 2, '.', '');
                if (bccomp($amount, '0', 2) <= 0) {
                    continue;
                }

                $invoice = Invoice::query()->lockForUpdate()->findOrFail($row['invoice_id']);
                if ((int) $invoice->customer_id !== (int) $header['customer_id']) {
                    throw new RuntimeException('Payment allocation customer mismatch.');
                }
                if (bccomp($amount, (string) $invoice->balance_due, 2) > 0) {
                    throw new RuntimeException("Allocation exceeds balance on {$invoice->invoice_number}.");
                }

                PaymentAllocation::query()->create([
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $amount,
                ]);

                $invoice->amount_paid = bcadd((string) $invoice->amount_paid, $amount, 2);
                $invoice->balance_due = bcsub((string) $invoice->balance_due, $amount, 2);
                $invoice->status = bccomp((string) $invoice->balance_due, '0', 2) === 0
                    ? 'paid'
                    : (bccomp((string) $invoice->amount_paid, '0', 2) > 0 ? 'partial' : $invoice->status);
                $invoice->save();

                $allocated = bcadd($allocated, $amount, 2);
            }

            if (bccomp($allocated, $paymentAmount, 2) > 0) {
                throw new RuntimeException('Allocations exceed payment amount.');
            }

            $payment->unapplied_amount = bcsub($paymentAmount, $allocated, 2);
            $payment->save();

            $customer = Customer::query()->lockForUpdate()->findOrFail($header['customer_id']);
            $customer->balance = bcsub((string) $customer->balance, $allocated, 2);
            $customer->save();

            if ($header['post_accounting'] ?? true) {
                $undeposited = $this->accounting->account('1050');
                $this->accounting->postBalancedEntry(
                    'JE-PMT-'.$payment->payment_number,
                    $header['payment_date'],
                    [
                        ['account' => $undeposited, 'debit' => $paymentAmount, 'credit' => 0],
                        ['account' => '1200', 'debit' => 0, 'credit' => $paymentAmount],
                    ],
                    'Payment '.$payment->payment_number,
                    Payment::class,
                    $payment->id,
                    $header['created_by'] ?? auth()->id()
                );
            }

            $payment = $payment->load('allocations');

            $this->audit->record('created', $payment, null, [
                'payment_number' => $payment->payment_number,
                'customer_id' => $payment->customer_id,
                'amount' => $payment->amount,
                'method' => $payment->method,
                'allocated' => $allocated,
            ], $header['created_by'] ?? auth()->id());

            return $payment;
        });
    }
}
