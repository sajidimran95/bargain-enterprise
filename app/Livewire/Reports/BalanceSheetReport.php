<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\VendorBill;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Balance Sheet')]
class BalanceSheetReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'today';
        $this->applyDatePreset('today');
    }

    /**
     * @return array{assets: Collection, liabilities: Collection, equity: Collection, asset_total: float, liability_total: float, equity_total: float}
     */
    protected function statement(): array
    {
        $ar = (float) Invoice::query()->whereIn('status', ['open', 'partial'])->sum('balance_due');
        $ap = (float) VendorBill::query()
            ->whereIn('status', ['open', 'partial'])
            ->where(function ($query) {
                $query->whereNull('memo')
                    ->orWhere('memo', 'not like', '%CREDIT%');
            })
            ->sum('balance_due');
        $inventory = (float) Item::query()->active()->get()->sum(fn (Item $item) => (float) $item->on_hand * (float) ($item->average_cost ?: 0));

        $assets = collect([
            ['name' => 'Accounts Receivable', 'amount' => $ar],
            ['name' => 'Inventory Asset', 'amount' => $inventory],
        ]);

        foreach ($this->journalBalances(['asset']) as $row) {
            $assets->push($row);
        }

        $liabilities = collect([
            ['name' => 'Accounts Payable', 'amount' => $ap],
        ]);

        foreach ($this->journalBalances(['liability']) as $row) {
            $liabilities->push($row);
        }

        $assetTotal = $assets->sum('amount');
        $liabilityTotal = $liabilities->sum('amount');
        $equityAmount = $assetTotal - $liabilityTotal;

        $equity = collect([
            ['name' => 'Owner Equity / Retained Earnings', 'amount' => $equityAmount],
        ]);

        return [
            'assets' => $assets,
            'liabilities' => $liabilities,
            'equity' => $equity,
            'asset_total' => $assetTotal,
            'liability_total' => $liabilityTotal,
            'equity_total' => $equityAmount,
        ];
    }

    /**
     * @param  list<string>  $types
     * @return Collection<int, array{name: string, amount: float}>
     */
    protected function journalBalances(array $types): Collection
    {
        return Account::query()
            ->whereIn('type', $types)
            ->where('is_active', true)
            ->withSum('journalLines as debit_sum', 'debit')
            ->withSum('journalLines as credit_sum', 'credit')
            ->orderBy('number')
            ->get()
            ->map(function (Account $account) {
                $debit = (float) ($account->debit_sum ?? 0);
                $credit = (float) ($account->credit_sum ?? 0);
                $amount = in_array($account->type, ['asset'], true)
                    ? $debit - $credit
                    : $credit - $debit;

                return [
                    'name' => $account->number.' · '.$account->name,
                    'amount' => $amount,
                ];
            })
            ->filter(fn (array $row) => abs($row['amount']) > 0.0001)
            ->values();
    }

    public function exportExcel(): StreamedResponse
    {
        $s = $this->statement();
        $rows = [];
        foreach (['Asset' => $s['assets'], 'Liability' => $s['liabilities'], 'Equity' => $s['equity']] as $section => $items) {
            foreach ($items as $item) {
                $rows[] = [$section, $item['name'], number_format($item['amount'], 2, '.', '')];
            }
        }

        return $this->exportReportCsv('balance-sheet.csv', ['Section', 'Account', 'Amount'], $rows);
    }

    public function render()
    {
        return view('livewire.reports.balance-sheet-report', [
            'statement' => $this->statement(),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => 'As of '.$this->to,
        ])->layoutData([
            'title' => 'Balance Sheet',
            'windowTitle' => 'Balance Sheet',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Balance Sheet';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return 'As of '.$this->to;
    }

    protected function reportPdfFilename(): string
    {
        return 'balance-sheet.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $s = $this->statement();
        $html = '<h3>Assets</h3><table><tbody>';
        foreach ($s['assets'] as $row) {
            $html .= '<tr><td>'.e($row['name']).'</td><td class="num">'.e(number_format($row['amount'], 2)).'</td></tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }
}
