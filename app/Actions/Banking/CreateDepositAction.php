<?php

namespace App\Actions\Banking;

use App\Models\BankAccount;
use App\Models\Deposit;
use App\Models\DepositItem;
use App\Models\Payment;
use App\Services\AccountingService;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CreateDepositAction
{
    public function __construct(
        protected AccountingService $accounting,
        protected AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  array<int, int>  $paymentIds
     */
    public function handle(array $header, array $paymentIds): Deposit
    {
        $header = Validator::make($header, [
            'number' => ['required', 'string', 'unique:deposits,number'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'deposit_date' => ['required', 'date'],
            'memo' => ['nullable', 'string'],
            'created_by' => ['nullable', 'exists:users,id'],
        ])->validate();

        $paymentIds = array_values(array_unique(array_map('intval', $paymentIds)));

        if ($paymentIds === []) {
            throw ValidationException::withMessages([
                'payments' => 'Select at least one undeposited payment.',
            ]);
        }

        return DB::transaction(function () use ($header, $paymentIds) {
            $bankAccount = BankAccount::query()->with('account')->findOrFail($header['bank_account_id']);

            if (! $bankAccount->account) {
                throw new RuntimeException('Bank account is missing a linked GL account.');
            }

            $payments = Payment::query()
                ->whereIn('id', $paymentIds)
                ->lockForUpdate()
                ->get();

            if ($payments->count() !== count($paymentIds)) {
                throw ValidationException::withMessages([
                    'payments' => 'One or more selected payments were not found.',
                ]);
            }

            foreach ($payments as $payment) {
                if ($payment->deposited) {
                    throw ValidationException::withMessages([
                        'payments' => "Payment {$payment->payment_number} is already deposited.",
                    ]);
                }
            }

            $total = '0.00';
            foreach ($payments as $payment) {
                $total = bcadd($total, (string) $payment->amount, 2);
            }

            $deposit = Deposit::query()->create([
                'number' => $header['number'],
                'bank_account_id' => $bankAccount->id,
                'deposit_date' => $header['deposit_date'],
                'total' => $total,
                'memo' => $header['memo'] ?? null,
            ]);

            foreach ($payments as $payment) {
                DepositItem::query()->create([
                    'deposit_id' => $deposit->id,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                ]);

                $payment->deposited = true;
                $payment->save();
            }

            // Dr Bank · Cr Undeposited Funds
            $this->accounting->postBalancedEntry(
                'JE-DEP-'.$deposit->number,
                $header['deposit_date'],
                [
                    ['account' => $bankAccount->account->number, 'debit' => $total, 'credit' => 0, 'memo' => 'Deposit'],
                    ['account' => '1050', 'debit' => 0, 'credit' => $total, 'memo' => 'Undeposited Funds'],
                ],
                'Deposit '.$deposit->number,
                Deposit::class,
                $deposit->id,
                $header['created_by'] ?? auth()->id()
            );

            $deposit = $deposit->load('items');

            $this->audit->record('created', $deposit, null, [
                'number' => $deposit->number,
                'bank_account_id' => $deposit->bank_account_id,
                'total' => $deposit->total,
                'payment_ids' => $paymentIds,
            ], $header['created_by'] ?? auth()->id());

            return $deposit;
        });
    }
}
