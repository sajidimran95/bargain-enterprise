<?php

namespace App\Actions\Sales;

use App\Models\Account;
use App\Models\CreditMemo;
use App\Models\CreditMemoRefund;
use App\Services\AccountingService;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class RefundCreditMemoAction
{
    public function __construct(
        protected AccountingService $accounting,
        protected AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handle(CreditMemo $creditMemo, array $payload): CreditMemoRefund
    {
        $payload = Validator::make($payload, [
            'refund_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', 'string', 'max:50'],
            'account_id' => ['nullable', 'exists:accounts,id'],
            'reference' => ['nullable', 'string', 'max:100'],
            'memo' => ['nullable', 'string'],
            'created_by' => ['nullable', 'exists:users,id'],
        ])->validate();

        return DB::transaction(function () use ($creditMemo, $payload) {
            $memo = CreditMemo::query()->whereKey($creditMemo->id)->lockForUpdate()->firstOrFail();

            $amount = number_format((float) $payload['amount'], 2, '.', '');

            if (bccomp($amount, (string) $memo->remaining_credit, 2) > 0) {
                throw new RuntimeException('Refund exceeds remaining credit.');
            }

            $account = isset($payload['account_id'])
                ? Account::query()->findOrFail($payload['account_id'])
                : $this->accounting->account('1000');

            $refund = CreditMemoRefund::query()->create([
                'credit_memo_id' => $memo->id,
                'refund_date' => $payload['refund_date'],
                'amount' => $amount,
                'method' => $payload['method'],
                'account_id' => $account->id,
                'reference' => $payload['reference'] ?? null,
                'memo' => $payload['memo'] ?? null,
                'created_by' => $payload['created_by'] ?? auth()->id(),
            ]);

            $memo->remaining_credit = bcsub((string) $memo->remaining_credit, $amount, 2);
            $memo->status = bccomp((string) $memo->remaining_credit, '0', 2) === 0 ? 'refunded' : 'open';
            $memo->save();

            // Clear AR credit with cash/bank payout: Dr AR / Cr Cash (or bank).
            $this->accounting->postBalancedEntry(
                'JE-CMR-'.$memo->credit_number.'-'.$refund->id,
                $payload['refund_date'],
                [
                    ['account' => '1200', 'debit' => $amount, 'credit' => 0, 'memo' => 'Refund credit'],
                    ['account' => $account, 'debit' => 0, 'credit' => $amount, 'memo' => 'Refund payout'],
                ],
                'Credit memo refund '.$memo->credit_number,
                CreditMemoRefund::class,
                $refund->id,
                $payload['created_by'] ?? auth()->id()
            );

            $this->audit->record('refunded', $memo->fresh(), [
                'remaining_credit' => bcadd((string) $memo->remaining_credit, $amount, 2),
            ], [
                'remaining_credit' => $memo->remaining_credit,
                'status' => $memo->status,
                'refund_id' => $refund->id,
                'amount' => $amount,
                'method' => $payload['method'],
            ], $payload['created_by'] ?? auth()->id());

            return $refund;
        });
    }
}
