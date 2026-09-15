<?php

namespace App\Actions\Banking;

use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Vendor;
use App\Services\AccountingService;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class CreateCheckAction
{
    public function __construct(
        protected AccountingService $accounting,
        protected AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $header
     */
    public function handle(array $header): Check
    {
        $header = Validator::make($header, [
            'check_number' => ['required', 'string', 'unique:checks,check_number'],
            'bank_account_id' => ['required', 'exists:bank_accounts,id'],
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'check_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'payee' => ['required', 'string', 'max:120'],
            'memo' => ['nullable', 'string'],
            'expense_account' => ['nullable', 'string'],
            'created_by' => ['nullable', 'exists:users,id'],
        ])->validate();

        return DB::transaction(function () use ($header) {
            $bankAccount = BankAccount::query()->with('account')->findOrFail($header['bank_account_id']);

            if (! $bankAccount->account) {
                throw new RuntimeException('Bank account is missing a linked GL account.');
            }

            $amount = number_format((float) $header['amount'], 2, '.', '');
            $payee = $header['payee'];

            if (! empty($header['vendor_id'])) {
                $vendor = Vendor::query()->find($header['vendor_id']);
                if ($vendor && blank($payee)) {
                    $payee = $vendor->display_name;
                }
            }

            $check = Check::query()->create([
                'check_number' => $header['check_number'],
                'bank_account_id' => $bankAccount->id,
                'vendor_id' => $header['vendor_id'] ?? null,
                'check_date' => $header['check_date'],
                'amount' => $amount,
                'payee' => $payee,
                'memo' => $header['memo'] ?? null,
            ]);

            // Write Check: Dr Expense (default 6000) · Cr Bank
            $expenseAccount = $header['expense_account'] ?? '6000';

            $this->accounting->postBalancedEntry(
                'JE-CHK-'.$check->check_number,
                $header['check_date'],
                [
                    ['account' => $expenseAccount, 'debit' => $amount, 'credit' => 0, 'memo' => 'Check expense'],
                    ['account' => $bankAccount->account->number, 'debit' => 0, 'credit' => $amount, 'memo' => 'Bank'],
                ],
                'Check '.$check->check_number.' — '.$payee,
                Check::class,
                $check->id,
                $header['created_by'] ?? auth()->id()
            );

            $this->audit->record('created', $check, null, [
                'check_number' => $check->check_number,
                'bank_account_id' => $check->bank_account_id,
                'vendor_id' => $check->vendor_id,
                'payee' => $check->payee,
                'amount' => $check->amount,
            ], $header['created_by'] ?? auth()->id());

            return $check;
        });
    }
}
