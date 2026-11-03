<?php

namespace App\Services;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Support\SystemAccounts;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AccountingService
{
    /**
     * @param  array<int, array{account: string|Account, debit?: float|string, credit?: float|string, memo?: ?string}>  $lines
     */
    public function postBalancedEntry(string $entryNumber, mixed $date, array $lines, ?string $memo = null, ?string $referenceType = null, ?int $referenceId = null, ?int $createdBy = null): JournalEntry
    {
        return DB::transaction(function () use ($entryNumber, $date, $lines, $memo, $referenceType, $referenceId, $createdBy) {
            $debitTotal = '0.00';
            $creditTotal = '0.00';
            $normalized = [];

            foreach ($lines as $line) {
                if ($line['account'] instanceof Account) {
                    $account = $line['account'];
                } else {
                    $accountKey = (string) $line['account'];
                    $account = SystemAccounts::findOrEnsure($accountKey);

                    if ($account === null) {
                        throw new RuntimeException(
                            "Chart of Accounts is missing [{$accountKey}]. Open Company → Chart of Accounts or run: php artisan db:seed --class=ChartOfAccountsSeeder"
                        );
                    }
                }

                $debit = number_format((float) ($line['debit'] ?? 0), 2, '.', '');
                $credit = number_format((float) ($line['credit'] ?? 0), 2, '.', '');
                $debitTotal = bcadd($debitTotal, $debit, 2);
                $creditTotal = bcadd($creditTotal, $credit, 2);

                $normalized[] = [
                    'account_id' => $account->id,
                    'debit' => $debit,
                    'credit' => $credit,
                    'memo' => $line['memo'] ?? null,
                ];
            }

            if (bccomp($debitTotal, $creditTotal, 2) !== 0) {
                throw new RuntimeException("Journal entry [{$entryNumber}] is out of balance ({$debitTotal} vs {$creditTotal}).");
            }

            $entry = JournalEntry::query()->create([
                'entry_number' => $entryNumber,
                'entry_date' => $date,
                'memo' => $memo,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'created_by' => $createdBy ?? auth()->id(),
            ]);

            foreach ($normalized as $line) {
                JournalLine::query()->create($line + ['journal_entry_id' => $entry->id]);
            }

            return $entry->load('lines');
        });
    }

    public function account(string $number): Account
    {
        return SystemAccounts::ensure($number);
    }
}
