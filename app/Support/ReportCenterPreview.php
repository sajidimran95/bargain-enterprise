<?php

namespace App\Support;

use App\Models\Account;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\JournalLine;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\VendorBill;
use App\Models\VendorBillLine;

class ReportCenterPreview
{
    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    public static function forRoute(?string $route, string $from, string $to): array
    {
        if ($route === null || $route === '') {
            return self::emptyPreview();
        }

        return match (true) {
            str_starts_with($route, 'reports.sales') || $route === 'reports.msa-sales-report' => self::salesPreview($from, $to),
            $route === 'reports.customers' || $route === 'reports.msa-customer-list' => self::customersPreview(),
            $route === 'reports.inventory' || $route === 'reports.msa-inventory' => self::inventoryPreview(),
            $route === 'reports.inventory-valuation' => self::inventoryValuationPreview(),
            $route === 'reports.open-balance' => self::openBalancePreview($from, $to),
            $route === 'reports.ar-aging' => self::arAgingPreview(),
            $route === 'reports.ap-aging' => self::apAgingPreview(),
            $route === 'reports.vendor-balance' => self::vendorBalancePreview($from, $to),
            $route === 'reports.purchase-by-item' => self::purchasesPreview($from, $to),
            $route === 'reports.profit-loss' => self::profitLossPreview($from, $to),
            $route === 'reports.balance-sheet' => self::balanceSheetPreview(),
            $route === 'reports.trial-balance' => self::trialBalancePreview($from, $to),
            $route === 'reports.general-ledger' => self::generalLedgerPreview($from, $to),
            $route === 'reports.cash-flow' => self::cashFlowPreview($from, $to),
            $route === 'sales-orders.index' || $route === 'sales-orders.fulfillment' => self::openSalesOrdersPreview(),
            $route === 'purchase-orders.index' => self::openPurchaseOrdersPreview(),
            $route === 'accounting.chart' => self::accountsPreview(),
            $route === 'accounting.journals' => self::journalsPreview($from, $to),
            $route === 'customers.statements' => self::openBalancePreview($from, $to),
            $route === 'invoices.index' => self::invoicesPreview($from, $to),
            default => self::genericActivityPreview($from, $to),
        };
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function emptyPreview(): array
    {
        return ['columns' => ['Info'], 'rows' => [['No preview']]];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function salesPreview(string $from, string $to): array
    {
        $rows = Invoice::query()
            ->with('customer')
            ->whereNot('status', 'draft')
            ->whereDate('invoice_date', '>=', $from)
            ->whereDate('invoice_date', '<=', $to)
            ->orderByDesc('invoice_date')
            ->limit(5)
            ->get()
            ->map(fn (Invoice $invoice) => [
                $invoice->invoice_date?->format('m/d/y') ?? '',
                $invoice->invoice_number,
                mb_strimwidth((string) ($invoice->customer?->display_name ?? ''), 0, 16, '…'),
                number_format((float) $invoice->total, 2),
            ])
            ->all();

        return [
            'columns' => ['Date', 'Num', 'Customer', 'Amount'],
            'rows' => $rows !== [] ? $rows : [['—', '—', 'No sales', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function customersPreview(): array
    {
        $rows = Customer::query()
            ->active()
            ->orderBy('display_name')
            ->limit(5)
            ->get()
            ->map(fn (Customer $customer) => [
                (string) ($customer->customer_number ?? ''),
                mb_strimwidth($customer->display_name, 0, 22, '…'),
                number_format((float) $customer->balance, 2),
            ])
            ->all();

        return [
            'columns' => ['#', 'Name', 'Balance'],
            'rows' => $rows !== [] ? $rows : [['—', 'No customers', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function inventoryPreview(): array
    {
        $rows = Item::query()
            ->active()
            ->orderBy('sku')
            ->limit(5)
            ->get()
            ->map(fn (Item $item) => [
                mb_strimwidth($item->sku, 0, 12, '…'),
                number_format((float) $item->on_hand, 1),
                number_format((float) $item->sales_price, 2),
            ])
            ->all();

        return [
            'columns' => ['SKU', 'Qty', 'Price'],
            'rows' => $rows !== [] ? $rows : [['—', '0', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function inventoryValuationPreview(): array
    {
        $rows = Item::query()
            ->active()
            ->orderBy('sku')
            ->limit(5)
            ->get()
            ->map(function (Item $item) {
                $qty = (float) $item->on_hand;
                $cost = (float) ($item->average_cost ?: $item->purchase_cost ?: 0);

                return [
                    mb_strimwidth($item->sku, 0, 12, '…'),
                    number_format($qty, 1),
                    number_format($qty * $cost, 2),
                ];
            })
            ->all();

        return [
            'columns' => ['SKU', 'Qty', 'Value'],
            'rows' => $rows !== [] ? $rows : [['—', '0', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function openBalancePreview(string $from, string $to): array
    {
        $rows = Invoice::query()
            ->with('customer')
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->whereDate('invoice_date', '>=', $from)
            ->whereDate('invoice_date', '<=', $to)
            ->orderByDesc('balance_due')
            ->limit(5)
            ->get()
            ->map(fn (Invoice $invoice) => [
                mb_strimwidth((string) ($invoice->customer?->display_name ?? ''), 0, 14, '…'),
                $invoice->invoice_number,
                number_format((float) $invoice->balance_due, 2),
            ])
            ->all();

        return [
            'columns' => ['Customer', 'Inv', 'Due'],
            'rows' => $rows !== [] ? $rows : [['—', '—', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function arAgingPreview(): array
    {
        $total = (float) Invoice::query()
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->sum('balance_due');

        $customers = Invoice::query()
            ->with('customer')
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderByDesc('balance_due')
            ->limit(4)
            ->get()
            ->map(fn (Invoice $invoice) => [
                mb_strimwidth((string) ($invoice->customer?->display_name ?? ''), 0, 14, '…'),
                number_format((float) $invoice->balance_due, 2),
            ])
            ->all();

        if ($customers === []) {
            $customers = [['No open A/R', '0.00']];
        }

        $customers[] = ['TOTAL', number_format($total, 2)];

        return ['columns' => ['Customer', 'Balance'], 'rows' => $customers];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function apAgingPreview(): array
    {
        $rows = VendorBill::query()
            ->with('vendor')
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderByDesc('balance_due')
            ->limit(5)
            ->get()
            ->map(fn (VendorBill $bill) => [
                mb_strimwidth((string) ($bill->vendor?->display_name ?? ''), 0, 14, '…'),
                number_format((float) $bill->balance_due, 2),
            ])
            ->all();

        return [
            'columns' => ['Vendor', 'Balance'],
            'rows' => $rows !== [] ? $rows : [['No open A/P', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function vendorBalancePreview(string $from, string $to): array
    {
        $rows = VendorBill::query()
            ->with('vendor')
            ->whereDate('bill_date', '>=', $from)
            ->whereDate('bill_date', '<=', $to)
            ->orderByDesc('bill_date')
            ->limit(5)
            ->get()
            ->map(fn (VendorBill $bill) => [
                mb_strimwidth((string) ($bill->vendor?->display_name ?? ''), 0, 14, '…'),
                number_format((float) $bill->balance_due, 2),
            ])
            ->all();

        return [
            'columns' => ['Vendor', 'Due'],
            'rows' => $rows !== [] ? $rows : [['—', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function purchasesPreview(string $from, string $to): array
    {
        $rows = VendorBillLine::query()
            ->with(['item', 'vendorBill'])
            ->whereHas('vendorBill', function ($q) use ($from, $to) {
                $q->whereNot('status', 'draft')
                    ->whereDate('bill_date', '>=', $from)
                    ->whereDate('bill_date', '<=', $to);
            })
            ->limit(5)
            ->get()
            ->map(fn (VendorBillLine $line) => [
                mb_strimwidth((string) ($line->item?->sku ?? '—'), 0, 10, '…'),
                number_format((float) $line->quantity, 1),
                number_format((float) $line->amount, 2),
            ])
            ->all();

        return [
            'columns' => ['SKU', 'Qty', 'Amt'],
            'rows' => $rows !== [] ? $rows : [['—', '0', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function profitLossPreview(string $from, string $to): array
    {
        $income = (float) Invoice::query()
            ->whereNot('status', 'draft')
            ->whereDate('invoice_date', '>=', $from)
            ->whereDate('invoice_date', '<=', $to)
            ->sum('total');
        $cogs = (float) VendorBill::query()
            ->whereNot('status', 'draft')
            ->whereDate('bill_date', '>=', $from)
            ->whereDate('bill_date', '<=', $to)
            ->sum('total');

        return [
            'columns' => ['Account', 'Amount'],
            'rows' => [
                ['Income', number_format($income, 2)],
                ['COGS', number_format($cogs, 2)],
                ['Net', number_format($income - $cogs, 2)],
            ],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function balanceSheetPreview(): array
    {
        $ar = (float) Invoice::query()->whereIn('status', ['open', 'partial'])->sum('balance_due');
        $ap = (float) VendorBill::query()->whereIn('status', ['open', 'partial'])->sum('balance_due');
        $inventory = (float) Item::query()->active()->get()->sum(
            fn (Item $item) => (float) $item->on_hand * (float) ($item->average_cost ?: $item->purchase_cost ?: 0)
        );

        return [
            'columns' => ['Account', 'Amount'],
            'rows' => [
                ['A/R', number_format($ar, 2)],
                ['Inventory', number_format($inventory, 2)],
                ['A/P', number_format($ap, 2)],
            ],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function trialBalancePreview(string $from, string $to): array
    {
        $lines = JournalLine::query()
            ->with('account')
            ->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '>=', $from)->whereDate('entry_date', '<=', $to))
            ->limit(5)
            ->get()
            ->map(fn (JournalLine $line) => [
                mb_strimwidth((string) ($line->account?->number ?? ''), 0, 8, '…'),
                number_format((float) $line->debit, 2),
                number_format((float) $line->credit, 2),
            ])
            ->all();

        return [
            'columns' => ['Acct', 'Debit', 'Credit'],
            'rows' => $lines !== [] ? $lines : [['—', '0.00', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function generalLedgerPreview(string $from, string $to): array
    {
        return self::trialBalancePreview($from, $to);
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function cashFlowPreview(string $from, string $to): array
    {
        $receipts = (float) Payment::query()
            ->whereDate('payment_date', '>=', $from)
            ->whereDate('payment_date', '<=', $to)
            ->sum('amount');

        return [
            'columns' => ['Line', 'Amount'],
            'rows' => [
                ['Receipts', number_format($receipts, 2)],
                ['Net', number_format($receipts, 2)],
            ],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function openSalesOrdersPreview(): array
    {
        $rows = SalesOrder::query()
            ->with('customer')
            ->where('status', 'open')
            ->orderByDesc('order_date')
            ->limit(5)
            ->get()
            ->map(fn (SalesOrder $order) => [
                $order->number,
                mb_strimwidth((string) ($order->customer?->display_name ?? ''), 0, 14, '…'),
                number_format((float) $order->total, 2),
            ])
            ->all();

        return [
            'columns' => ['SO #', 'Customer', 'Total'],
            'rows' => $rows !== [] ? $rows : [['—', 'No open SOs', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function openPurchaseOrdersPreview(): array
    {
        $rows = PurchaseOrder::query()
            ->with('vendor')
            ->whereIn('status', ['open', 'partial', 'draft'])
            ->orderByDesc('order_date')
            ->limit(5)
            ->get()
            ->map(fn (PurchaseOrder $order) => [
                $order->number,
                mb_strimwidth((string) ($order->vendor?->display_name ?? ''), 0, 14, '…'),
                number_format((float) $order->total, 2),
            ])
            ->all();

        return [
            'columns' => ['PO #', 'Vendor', 'Total'],
            'rows' => $rows !== [] ? $rows : [['—', 'No open POs', '0.00']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function accountsPreview(): array
    {
        $rows = Account::query()
            ->where('is_active', true)
            ->orderBy('number')
            ->limit(5)
            ->get()
            ->map(fn ($account) => [
                $account->number,
                mb_strimwidth($account->name, 0, 16, '…'),
                $account->type,
            ])
            ->all();

        return [
            'columns' => ['#', 'Name', 'Type'],
            'rows' => $rows !== [] ? $rows : [['—', '—', '—']],
        ];
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function journalsPreview(string $from, string $to): array
    {
        return self::trialBalancePreview($from, $to);
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function invoicesPreview(string $from, string $to): array
    {
        return self::salesPreview($from, $to);
    }

    /**
     * @return array{columns: list<string>, rows: list<list<string>>}
     */
    private static function genericActivityPreview(string $from, string $to): array
    {
        return self::salesPreview($from, $to);
    }

    /**
     * @return array{0: string, 1: string}
     */
    public static function dateRange(string $preset): array
    {
        return QbDatePresets::dateStrings($preset);
    }
}
