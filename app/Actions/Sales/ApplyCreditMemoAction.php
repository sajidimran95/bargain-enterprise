<?php

namespace App\Actions\Sales;

use App\Models\CreditMemo;
use App\Models\CreditMemoAllocation;
use App\Models\Invoice;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class ApplyCreditMemoAction
{
    public function __construct(protected AuditLogger $audit) {}

    /**
     * @param  array<int, array{invoice_id: int, amount: float|string}>  $allocations
     */
    public function handle(CreditMemo $creditMemo, array $allocations): CreditMemo
    {
        if ($allocations === []) {
            throw ValidationException::withMessages([
                'allocations' => 'Enter at least one invoice allocation.',
            ]);
        }

        return DB::transaction(function () use ($creditMemo, $allocations) {
            $memo = CreditMemo::query()->whereKey($creditMemo->id)->lockForUpdate()->firstOrFail();

            if (! $memo->hasRemainingCredit()) {
                throw new RuntimeException('Credit memo has no remaining credit.');
            }

            $applied = '0.00';

            foreach ($allocations as $row) {
                $amount = number_format((float) $row['amount'], 2, '.', '');
                if (bccomp($amount, '0', 2) <= 0) {
                    continue;
                }

                $invoice = Invoice::query()->lockForUpdate()->findOrFail($row['invoice_id']);

                if ((int) $invoice->customer_id !== (int) $memo->customer_id) {
                    throw new RuntimeException('Invoice customer does not match credit memo.');
                }

                if (bccomp($amount, (string) $invoice->balance_due, 2) > 0) {
                    throw new RuntimeException("Allocation exceeds balance on {$invoice->invoice_number}.");
                }

                $remainingAfter = bcsub((string) $memo->remaining_credit, bcadd($applied, $amount, 2), 2);
                if (bccomp($remainingAfter, '0', 2) < 0) {
                    throw new RuntimeException('Allocations exceed remaining credit.');
                }

                CreditMemoAllocation::query()->create([
                    'credit_memo_id' => $memo->id,
                    'invoice_id' => $invoice->id,
                    'amount' => $amount,
                ]);

                $invoice->amount_paid = bcadd((string) $invoice->amount_paid, $amount, 2);
                $invoice->balance_due = bcsub((string) $invoice->balance_due, $amount, 2);
                $invoice->status = bccomp((string) $invoice->balance_due, '0', 2) === 0
                    ? 'paid'
                    : (bccomp((string) $invoice->amount_paid, '0', 2) > 0 ? 'partial' : $invoice->status);
                $invoice->save();

                $applied = bcadd($applied, $amount, 2);
            }

            if (bccomp($applied, '0', 2) <= 0) {
                throw ValidationException::withMessages([
                    'allocations' => 'Enter at least one positive allocation amount.',
                ]);
            }

            $memo->remaining_credit = bcsub((string) $memo->remaining_credit, $applied, 2);
            $memo->status = bccomp((string) $memo->remaining_credit, '0', 2) === 0 ? 'applied' : 'open';
            $memo->save();

            // Customer balance already reduced when the credit memo was created.
            $memo = $memo->load(['allocations', 'lines']);

            $this->audit->record('applied', $memo, [
                'remaining_credit' => bcadd((string) $memo->remaining_credit, $applied, 2),
                'status' => 'open',
            ], [
                'remaining_credit' => $memo->remaining_credit,
                'status' => $memo->status,
                'applied' => $applied,
                'allocations' => $allocations,
            ]);

            return $memo;
        });
    }
}
