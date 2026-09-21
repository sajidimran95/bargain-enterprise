<?php

namespace App\Support;

use App\Models\InvoiceLine;
use Illuminate\Support\Collection;

/**
 * Dynamic QuickBooks Desktop sales report builder.
 * Columns come from config per layout; cell values resolve by header name.
 */
class QbSalesReportExport
{
    /**
     * @return array<string, string>
     */
    public static function layouts(): array
    {
        return config('qb_sales_reports.layouts', []);
    }

    public static function filename(string $layout): string
    {
        return (string) config(
            'qb_sales_reports.filenames.'.$layout,
            'sales-by-item-detail.xlsm'
        );
    }

    public static function title(string $layout): string
    {
        return (string) (self::layouts()[$layout] ?? 'Sales by Item Detail');
    }

    /**
     * @return list<string>
     */
    public static function headersFor(string $layout): array
    {
        /** @var list<string> $columns */
        $columns = config('qb_sales_reports.columns.'.$layout, config('qb_sales_reports.columns.item_detail', []));

        return array_values($columns);
    }

    /**
     * @return list<string>
     */
    public static function numericHeaders(): array
    {
        return array_values(config('qb_sales_reports.numeric_columns', []));
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    public function build(string $layout, Collection $lines): array
    {
        if (! array_key_exists($layout, self::layouts())) {
            $layout = 'item_detail';
        }

        return match ($layout) {
            'customer_detail' => $this->groupedDetail($layout, $lines, fn (InvoiceLine $line) => (string) ($line->invoice?->customer_id ?: 'none'), fn (InvoiceLine $line) => $line->invoice?->customer?->display_name ?: 'Unassigned'),
            'ship_to_detail' => $this->shipToDetail($layout, $lines),
            'rep_detail' => $this->groupedDetail($layout, $lines, fn (InvoiceLine $line) => $line->invoice?->createdBy?->name ?: 'No sales rep', fn (InvoiceLine $line) => $line->invoice?->createdBy?->name ?: 'No sales rep'),
            'item_summary' => $this->itemSummary($layout, $lines),
            'customer_summary' => $this->customerSummary($layout, $lines),
            default => $this->itemDetail($layout, $lines),
        };
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function itemDetail(string $layout, Collection $lines): array
    {
        $headers = self::headersFor($layout);
        $rows = [];
        $grand = $this->emptyTotals();

        foreach ($lines->groupBy(fn (InvoiceLine $line) => $this->typeLabel($line)) as $typeLabel => $typeLines) {
            $rows[] = $this->labelRow($layout, (string) $typeLabel);
            $typeTotals = $this->emptyTotals();

            foreach ($typeLines->groupBy(fn (InvoiceLine $line) => $line->item_id ?: 'none') as $itemLines) {
                $label = $this->itemLabel($itemLines->first());
                $rows[] = $this->labelRow($layout, $label);
                $itemTotals = $this->emptyTotals();

                foreach ($itemLines as $line) {
                    $rows[] = $this->detailRow($layout, $line);
                    $this->addLineToTotals($itemTotals, $line);
                }

                $rows[] = $this->totalRow($layout, 'Total '.$label, $itemTotals);
                $this->mergeTotals($typeTotals, $itemTotals);
            }

            $rows[] = $this->totalRow($layout, 'Total '.$typeLabel, $typeTotals);
            $this->mergeTotals($grand, $typeTotals);
        }

        if ($rows !== []) {
            $rows[] = $this->totalRow($layout, 'TOTAL', $grand, grand: true);
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @param  callable(InvoiceLine): string  $groupKey
     * @param  callable(InvoiceLine): string  $groupLabel
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function groupedDetail(string $layout, Collection $lines, callable $groupKey, callable $groupLabel): array
    {
        $headers = self::headersFor($layout);
        $rows = [];
        $grand = $this->emptyTotals();

        $sorted = $lines->sortBy(fn (InvoiceLine $line) => $groupLabel($line));

        foreach ($sorted->groupBy($groupKey) as $groupLines) {
            /** @var Collection<int, InvoiceLine> $groupLines */
            $label = $groupLabel($groupLines->first());
            $rows[] = $this->labelRow($layout, $label);
            $groupTotals = $this->emptyTotals();

            foreach ($groupLines->sortBy(fn (InvoiceLine $line) => $line->invoice?->invoice_date?->timestamp ?? 0) as $line) {
                $rows[] = $this->detailRow($layout, $line);
                $this->addLineToTotals($groupTotals, $line);
            }

            $rows[] = $this->totalRow($layout, 'Total '.$label, $groupTotals);
            $this->mergeTotals($grand, $groupTotals);
        }

        if ($rows !== []) {
            $rows[] = $this->totalRow($layout, 'TOTAL', $grand, grand: true);
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function shipToDetail(string $layout, Collection $lines): array
    {
        $headers = self::headersFor($layout);
        $rows = [];
        $grand = $this->emptyTotals();

        $byShip = $lines->groupBy(function (InvoiceLine $line) {
            $c = $line->invoice?->customer;

            return trim(($c?->bill_to_city ?: 'Unknown').' , '.($c?->bill_to_state ?: ''));
        });

        foreach ($byShip as $shipLabel => $shipLines) {
            $rows[] = $this->labelRow($layout, (string) $shipLabel);
            $shipTotals = $this->emptyTotals();

            foreach ($shipLines->groupBy(fn (InvoiceLine $line) => $line->invoice?->customer_id ?: 'none') as $customerLines) {
                $name = $customerLines->first()?->invoice?->customer?->display_name ?: 'Unassigned';
                $rows[] = $this->labelRow($layout, $name);
                $groupTotals = $this->emptyTotals();

                foreach ($customerLines as $line) {
                    $rows[] = $this->detailRow($layout, $line);
                    $this->addLineToTotals($groupTotals, $line);
                }

                $rows[] = $this->totalRow($layout, 'Total '.$name, $groupTotals);
                $this->mergeTotals($shipTotals, $groupTotals);
            }

            $rows[] = $this->totalRow($layout, 'Total '.$shipLabel, $shipTotals);
            $this->mergeTotals($grand, $shipTotals);
        }

        if ($rows !== []) {
            $rows[] = $this->totalRow($layout, 'TOTAL', $grand, grand: true);
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function itemSummary(string $layout, Collection $lines): array
    {
        $headers = self::headersFor($layout);
        $rows = [];
        $grand = $this->emptyTotals();
        $totalSales = $this->totalSales($lines);

        foreach ($lines->groupBy(fn (InvoiceLine $line) => $this->typeLabel($line)) as $typeLabel => $typeLines) {
            $rows[] = $this->labelRow($layout, (string) $typeLabel);
            $typeTotals = $this->emptyTotals();

            foreach ($typeLines->groupBy(fn (InvoiceLine $line) => $line->item_id ?: 'none') as $itemLines) {
                $agg = $this->aggregateLines($itemLines, $totalSales);
                $rows[] = $this->summaryRow($layout, $this->itemLabel($itemLines->first()), $agg, $itemLines->first());
                $this->mergeTotals($typeTotals, $agg);
            }

            $rows[] = $this->totalRow($layout, 'Total '.$typeLabel, $typeTotals, withMetrics: true, totalSales: $totalSales);
            $this->mergeTotals($grand, $typeTotals);
        }

        if ($rows !== []) {
            $rows[] = $this->totalRow($layout, 'TOTAL', $grand, grand: true, withMetrics: true, totalSales: $totalSales);
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function customerSummary(string $layout, Collection $lines): array
    {
        $headers = self::headersFor($layout);
        $rows = [];
        $grand = $this->emptyTotals();
        $totalSales = $this->totalSales($lines);

        $byCustomer = $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->customer?->display_name ?? '')
            ->groupBy(fn (InvoiceLine $line) => $line->invoice?->customer_id ?: 'none');

        foreach ($byCustomer as $customerLines) {
            $name = $customerLines->first()?->invoice?->customer?->display_name ?: 'Unassigned';
            $agg = $this->aggregateLines($customerLines, $totalSales);
            $rows[] = $this->summaryRow($layout, $name, $agg, $customerLines->first());
            $this->mergeTotals($grand, $agg);
        }

        if ($rows !== []) {
            $rows[] = $this->totalRow($layout, 'TOTAL', $grand, grand: true, withMetrics: true, totalSales: $totalSales);
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @return list<string|float|int|null>
     */
    protected function detailRow(string $layout, InvoiceLine $line): array
    {
        return $this->mapHeaders($layout, $this->lineValues($line));
    }

    /**
     * @param  array{qty: string, amount: string, balance: string, cogs: string, pct_sales: string, margin_pct: string}  $agg
     * @return list<string|float|int|null>
     */
    protected function summaryRow(string $layout, string $label, array $agg, ?InvoiceLine $sample): array
    {
        $qty = $agg['qty'];
        $amount = $agg['amount'];
        $cogs = $agg['cogs'];
        $avgPrice = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($amount, $qty, 4);
        $avgCogs = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($cogs, $qty, 4);
        $margin = bcsub($amount, $cogs, 2);
        $customer = $sample?->invoice?->customer;

        return $this->mapHeaders($layout, array_merge(
            $sample ? $this->lineValues($sample) : [],
            [
                '' => $label,
                'Type' => '',
                'Date' => '',
                'Num' => '',
                'Memo' => '',
                'Name' => $customer?->display_name,
                'Name Address' => $this->nameAddress($customer),
                'Name Street1' => $customer?->bill_to_street1,
                'Name City' => $customer?->bill_to_city,
                'Name State' => $customer?->bill_to_state,
                'Name Zip' => $customer?->bill_to_zip,
                'Name Fax #' => $customer?->fax,
                'Ship To Address 1' => $customer?->bill_to_street1,
                'Ship To Address 2' => $customer?->bill_to_street2,
                'Ship Zip' => $customer?->bill_to_zip,
                'Item' => $sample ? $this->itemCodeLabel($sample) : '',
                'Account' => $sample?->item?->income_account,
                'Qty' => $this->n4($qty),
                'U/M' => $sample ? $this->uom($sample) : '',
                'Sales Price' => $this->n2($avgPrice),
                'Amount' => $this->n2($amount),
                'Balance' => $this->n2($agg['balance']),
                '% of Sales' => $this->n4($agg['pct_sales']),
                'Avg Price' => $this->n2($avgPrice),
                'COGS' => $this->n2($cogs),
                'Avg COGS' => $this->n2($avgCogs),
                'Gross Margin' => $this->n2($margin),
                'Gross Margin %' => $this->n4($agg['margin_pct']),
            ]
        ));
    }

    /**
     * @return list<string|float|int|null>
     */
    protected function labelRow(string $layout, string $label): array
    {
        return $this->mapHeaders($layout, ['' => $label]);
    }

    /**
     * @param  array{qty: string, amount: string, balance: string, cogs: string}  $totals
     * @return list<string|float|int|null>
     */
    protected function totalRow(
        string $layout,
        string $label,
        array $totals,
        bool $grand = false,
        bool $withMetrics = false,
        string $totalSales = '0.00',
    ): array {
        $qty = $totals['qty'];
        $amount = $totals['amount'];
        $cogs = $totals['cogs'];
        $avgPrice = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($amount, $qty, 4);
        $avgCogs = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($cogs, $qty, 4);
        $margin = bcsub($amount, $cogs, 2);
        $pctSales = $withMetrics
            ? ($grand ? '1' : (bccomp($totalSales, '0', 2) === 0 ? '0' : bcdiv($amount, $totalSales, 6)))
            : '';
        $marginPct = $withMetrics
            ? (bccomp($amount, '0', 2) === 0 ? '0' : bcdiv($margin, $amount, 6))
            : '';

        return $this->mapHeaders($layout, [
            '' => $label,
            'Qty' => $this->n4($qty),
            'Amount' => $this->n2($amount),
            'Balance' => $this->n2($totals['balance']),
            'Sales Price' => $withMetrics ? $this->n2($avgPrice) : '',
            '% of Sales' => $pctSales === '' ? '' : $this->n4($pctSales),
            'Avg Price' => $withMetrics ? $this->n2($avgPrice) : '',
            'COGS' => $this->n2($cogs),
            'Avg COGS' => $withMetrics ? $this->n2($avgCogs) : '',
            'Gross Margin' => $this->n2($margin),
            'Gross Margin %' => $marginPct === '' ? '' : $this->n4($marginPct),
        ]);
    }

    /**
     * Resolve every configured header dynamically from a value map.
     *
     * @param  array<string, mixed>  $values
     * @return list<string|float|int|null>
     */
    protected function mapHeaders(string $layout, array $values): array
    {
        $row = [];
        foreach (self::headersFor($layout) as $header) {
            $row[] = $values[$header] ?? '';
        }

        return $row;
    }

    /**
     * Dynamic field bag for one invoice line (only headers present in a layout are used).
     *
     * @return array<string, mixed>
     */
    protected function lineValues(InvoiceLine $line): array
    {
        $customer = $line->invoice?->customer;
        $share = $this->lineBalanceShare($line);
        $cogs = $this->lineCogs($line);
        $qty = (string) $line->quantity;
        $amount = (string) $line->amount;
        $avgPrice = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($amount, $qty, 4);
        $avgCogs = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($cogs, $qty, 4);
        $margin = bcsub($amount, $cogs, 2);
        $marginPct = bccomp($amount, '0', 2) === 0 ? '0' : bcdiv($margin, $amount, 6);

        return [
            '' => '',
            'Type' => 'Invoice',
            'Date' => $line->invoice?->invoice_date?->format('m/d/Y'),
            'Num' => $line->invoice?->invoice_number,
            'Memo' => $line->invoice?->memo ?: ($line->description ?: $line->item?->sales_description ?: $line->item?->name),
            'Name' => $customer?->display_name,
            'Name Address' => $this->nameAddress($customer),
            'Name Street1' => $customer?->bill_to_street1,
            'Name City' => $customer?->bill_to_city,
            'Name State' => $customer?->bill_to_state,
            'Name Zip' => $customer?->bill_to_zip,
            'Name Fax #' => $customer?->fax,
            'Ship To Address 1' => $customer?->bill_to_street1,
            'Ship To Address 2' => $customer?->bill_to_street2,
            'Ship Zip' => $customer?->bill_to_zip,
            'Item' => $this->itemCodeLabel($line),
            'Account' => $line->item?->income_account ?: $line->item?->cogs_account,
            'Qty' => $this->n4($line->quantity),
            'U/M' => $this->uom($line),
            'Sales Price' => $this->n2($line->rate),
            'Amount' => $this->n2($line->amount),
            'Balance' => $share,
            '% of Sales' => '',
            'Avg Price' => $this->n2($avgPrice),
            'COGS' => $this->n2($cogs),
            'Avg COGS' => $this->n2($avgCogs),
            'Gross Margin' => $this->n2($margin),
            'Gross Margin %' => $this->n4($marginPct),
        ];
    }

    /**
     * @return array{qty: string, amount: string, balance: string, cogs: string, pct_sales: string, margin_pct: string}
     */
    protected function emptyTotals(): array
    {
        return [
            'qty' => '0.0000',
            'amount' => '0.00',
            'balance' => '0.00',
            'cogs' => '0.00',
            'pct_sales' => '0',
            'margin_pct' => '0',
        ];
    }

    /**
     * @param  array{qty: string, amount: string, balance: string, cogs: string, pct_sales: string, margin_pct: string}  $totals
     */
    protected function addLineToTotals(array &$totals, InvoiceLine $line): void
    {
        $totals['qty'] = bcadd($totals['qty'], (string) $line->quantity, 4);
        $totals['amount'] = bcadd($totals['amount'], (string) $line->amount, 2);
        $totals['balance'] = bcadd($totals['balance'], $this->lineBalanceShare($line), 2);
        $totals['cogs'] = bcadd($totals['cogs'], $this->lineCogs($line), 2);
    }

    /**
     * @param  array{qty: string, amount: string, balance: string, cogs: string, pct_sales: string, margin_pct: string}  $into
     * @param  array{qty: string, amount: string, balance: string, cogs: string, pct_sales?: string, margin_pct?: string}  $from
     */
    protected function mergeTotals(array &$into, array $from): void
    {
        $into['qty'] = bcadd($into['qty'], $from['qty'], 4);
        $into['amount'] = bcadd($into['amount'], $from['amount'], 2);
        $into['balance'] = bcadd($into['balance'], $from['balance'], 2);
        $into['cogs'] = bcadd($into['cogs'], $from['cogs'], 2);
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{qty: string, amount: string, balance: string, cogs: string, pct_sales: string, margin_pct: string}
     */
    protected function aggregateLines(Collection $lines, string $totalSales): array
    {
        $agg = $this->emptyTotals();
        foreach ($lines as $line) {
            $this->addLineToTotals($agg, $line);
        }
        $agg['pct_sales'] = bccomp($totalSales, '0', 2) === 0 ? '0' : bcdiv($agg['amount'], $totalSales, 6);
        $margin = bcsub($agg['amount'], $agg['cogs'], 2);
        $agg['margin_pct'] = bccomp($agg['amount'], '0', 2) === 0 ? '0' : bcdiv($margin, $agg['amount'], 6);

        return $agg;
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     */
    protected function totalSales(Collection $lines): string
    {
        $total = '0.00';
        foreach ($lines as $line) {
            $total = bcadd($total, (string) $line->amount, 2);
        }

        return $total;
    }

    protected function lineCogs(InvoiceLine $line): string
    {
        $unitCost = (string) ($line->item?->average_cost ?: $line->item?->purchase_cost ?: 0);

        return bcmul($unitCost, (string) $line->quantity, 4);
    }

    protected function lineBalanceShare(InvoiceLine $line): string
    {
        $invoice = $line->invoice;
        if (! $invoice) {
            return '0.00';
        }

        $total = (string) $invoice->total;
        $balance = (string) $invoice->balance_due;
        if (bccomp($total, '0', 2) <= 0 || bccomp($balance, '0', 2) === 0) {
            return '0.00';
        }

        return $this->n2(bcmul($balance, bcdiv((string) $line->amount, $total, 8), 8));
    }

    protected function typeLabel(InvoiceLine $line): string
    {
        return (string) ($line->item?->itemType?->label ?: $line->item?->type ?: 'Inventory');
    }

    protected function itemLabel(?InvoiceLine $line): string
    {
        $item = $line?->item;
        if (! $item) {
            return 'Unassigned';
        }
        $code = $item->barcode ?: $item->sku ?: '';
        $description = $item->sales_description ?: $item->name ?: '';

        return trim($code.' ('.$description.')');
    }

    protected function itemCodeLabel(InvoiceLine $line): string
    {
        $item = $line->item;
        if (! $item) {
            return '';
        }
        $code = $item->barcode ?: $item->sku ?: '';
        $name = $item->name ?: $item->sales_description ?: '';

        return trim($code.' | '.$name, ' |');
    }

    protected function uom(InvoiceLine $line): string
    {
        return (string) ($line->item?->unitOfMeasure?->abbreviation
            ?: $line->item?->unitOfMeasure?->name
            ?: '');
    }

    protected function nameAddress(mixed $customer): string
    {
        if (! $customer) {
            return '';
        }

        return implode(', ', array_filter([
            $customer->company_name ?: $customer->display_name,
            $customer->bill_to_street1,
            $customer->bill_to_street2,
            trim(implode(', ', array_filter([
                $customer->bill_to_city,
                $customer->bill_to_state,
                $customer->bill_to_zip,
            ]))),
            $customer->bill_to_country,
        ], fn ($part) => filled($part)));
    }

    protected function n2(mixed $value): string
    {
        return number_format((float) $value, 2, '.', '');
    }

    protected function n4(mixed $value): string
    {
        return number_format((float) $value, 4, '.', '');
    }
}
