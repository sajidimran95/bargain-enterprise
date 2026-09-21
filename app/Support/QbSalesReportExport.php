<?php

namespace App\Support;

use App\Models\InvoiceLine;
use Illuminate\Support\Collection;

/**
 * Builds QuickBooks Desktop sales report row layouts for .xlsm export.
 */
class QbSalesReportExport
{
    /**
     * @return array<string, string>
     */
    public static function layouts(): array
    {
        return [
            'item_detail' => 'Sales by Item Detail',
            'customer_detail' => 'Sales by Customer Detail',
            'ship_to_detail' => 'Sales by Ship To Address',
            'rep_detail' => 'Sales by Rep Detail',
            'item_summary' => 'Sales by Item Summary',
            'customer_summary' => 'Sales by Customer Summary',
        ];
    }

    public static function filename(string $layout): string
    {
        return match ($layout) {
            'customer_detail' => 'sales-by-customer-detail.xlsm',
            'ship_to_detail' => 'sales-by-ship-to-address.xlsm',
            'rep_detail' => 'sales-by-rep-detail.xlsm',
            'item_summary' => 'sales-by-item-summary.xlsm',
            'customer_summary' => 'sales-by-customer-summary.xlsm',
            default => 'sales-by-item-detail.xlsm',
        };
    }

    public static function title(string $layout): string
    {
        return self::layouts()[$layout] ?? 'Sales by Item Detail';
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    public function build(string $layout, Collection $lines): array
    {
        return match ($layout) {
            'customer_detail' => $this->customerDetail($lines),
            'ship_to_detail' => $this->shipToDetail($lines),
            'rep_detail' => $this->repDetail($lines),
            'item_summary' => $this->itemSummary($lines),
            'customer_summary' => $this->customerSummary($lines),
            default => $this->itemDetail($lines),
        };
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function itemDetail(Collection $lines): array
    {
        $headers = ['', 'Type', 'Date', 'Num', 'Memo', 'Name', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance'];
        $rows = [];
        $grandQty = '0.0000';
        $grandAmount = '0.00';
        $grandBalance = '0.00';

        $byType = $lines->groupBy(fn (InvoiceLine $line) => $line->item?->itemType?->label
            ?: $line->item?->type
            ?: 'Inventory');

        foreach ($byType as $typeLabel => $typeLines) {
            $rows[] = $this->pad([$typeLabel], count($headers));
            $typeQty = '0.0000';
            $typeAmount = '0.00';
            $typeBalance = '0.00';

            foreach ($typeLines->groupBy(fn (InvoiceLine $line) => $line->item_id ?: 'none') as $itemLines) {
                $label = $this->itemLabel($itemLines->first());
                $rows[] = $this->pad([$label], count($headers));
                $qty = '0.0000';
                $amount = '0.00';
                $balance = '0.00';

                foreach ($itemLines as $line) {
                    $share = $this->lineBalanceShare($line);
                    $rows[] = [
                        '',
                        'Invoice',
                        $line->invoice?->invoice_date?->format('m/d/Y'),
                        $line->invoice?->invoice_number,
                        $line->invoice?->memo,
                        $line->invoice?->customer?->display_name,
                        $this->n4($line->quantity),
                        $this->uom($line),
                        $this->n2($line->rate),
                        $this->n2($line->amount),
                        $share,
                    ];
                    $qty = bcadd($qty, (string) $line->quantity, 4);
                    $amount = bcadd($amount, (string) $line->amount, 2);
                    $balance = bcadd($balance, $share, 2);
                }

                $rows[] = [
                    'Total '.$label,
                    '', '', '', '', '',
                    $this->n4($qty), '', '',
                    $this->n2($amount),
                    $this->n2($balance),
                ];
                $typeQty = bcadd($typeQty, $qty, 4);
                $typeAmount = bcadd($typeAmount, $amount, 2);
                $typeBalance = bcadd($typeBalance, $balance, 2);
            }

            $rows[] = [
                'Total '.$typeLabel,
                '', '', '', '', '',
                $this->n4($typeQty), '', '',
                $this->n2($typeAmount),
                $this->n2($typeBalance),
            ];
            $grandQty = bcadd($grandQty, $typeQty, 4);
            $grandAmount = bcadd($grandAmount, $typeAmount, 2);
            $grandBalance = bcadd($grandBalance, $typeBalance, 2);
        }

        if ($rows !== []) {
            $rows[] = [
                'TOTAL',
                '', '', '', '', '',
                $this->n4($grandQty), '', '',
                $this->n2($grandAmount),
                $this->n2($grandBalance),
            ];
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function customerDetail(Collection $lines): array
    {
        $headers = [
            '', 'Type', 'Date', 'Num', 'Name Address', 'Name Street1', 'Name City', 'Name State', 'Name Zip',
            'Name Fax #', 'Memo', 'Name', 'Item', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance',
        ];
        $rows = [];
        $grandQty = '0.0000';
        $grandAmount = '0.00';
        $grandBalance = '0.00';

        $byCustomer = $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->customer?->display_name ?? '')
            ->groupBy(fn (InvoiceLine $line) => $line->invoice?->customer_id ?: 'none');

        foreach ($byCustomer as $customerLines) {
            $customer = $customerLines->first()?->invoice?->customer;
            $name = $customer?->display_name ?: 'Unassigned';
            $rows[] = $this->pad([$name], count($headers));

            $qty = '0.0000';
            $amount = '0.00';
            $balance = '0.00';

            foreach ($customerLines->sortBy(fn (InvoiceLine $line) => $line->invoice?->invoice_date?->timestamp ?? 0) as $line) {
                $share = $this->lineBalanceShare($line);
                $rows[] = [
                    '',
                    'Invoice',
                    $line->invoice?->invoice_date?->format('m/d/Y'),
                    $line->invoice?->invoice_number,
                    $this->nameAddress($customer),
                    $customer?->bill_to_street1,
                    $customer?->bill_to_city,
                    $customer?->bill_to_state,
                    $customer?->bill_to_zip,
                    $customer?->fax,
                    $line->description ?: $line->item?->sales_description ?: $line->item?->name,
                    $name,
                    $this->itemCodeLabel($line),
                    $this->n4($line->quantity),
                    $this->uom($line),
                    $this->n2($line->rate),
                    $this->n2($line->amount),
                    $share,
                ];
                $qty = bcadd($qty, (string) $line->quantity, 4);
                $amount = bcadd($amount, (string) $line->amount, 2);
                $balance = bcadd($balance, $share, 2);
            }

            $rows[] = [
                'Total '.$name,
                '', '', '', '', '', '', '', '', '', '', '', '',
                $this->n4($qty), '', '',
                $this->n2($amount),
                $this->n2($balance),
            ];
            $grandQty = bcadd($grandQty, $qty, 4);
            $grandAmount = bcadd($grandAmount, $amount, 2);
            $grandBalance = bcadd($grandBalance, $balance, 2);
        }

        if ($rows !== []) {
            $rows[] = [
                'TOTAL',
                '', '', '', '', '', '', '', '', '', '', '', '',
                $this->n4($grandQty), '', '',
                $this->n2($grandAmount),
                $this->n2($grandBalance),
            ];
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function shipToDetail(Collection $lines): array
    {
        $headers = [
            '', 'Type', 'Date', 'Num', 'Ship To Address 1', 'Ship To Address 2', 'Ship Zip',
            'Name Address', 'Name Street1', 'Name City', 'Name State', 'Name Zip', 'Name Fax #',
            'Item', 'Account', 'Qty', 'Sales Price', 'Amount',
        ];
        $rows = [];
        $grandQty = '0.0000';
        $grandAmount = '0.00';

        $byShip = $lines->groupBy(function (InvoiceLine $line) {
            $c = $line->invoice?->customer;

            return trim(($c?->bill_to_city ?: 'Unknown').' , '.($c?->bill_to_state ?: ''));
        });

        foreach ($byShip as $shipLabel => $shipLines) {
            $rows[] = $this->pad([$shipLabel], count($headers));
            $shipQty = '0.0000';
            $shipAmount = '0.00';

            foreach ($shipLines->groupBy(fn (InvoiceLine $line) => $line->invoice?->customer_id ?: 'none') as $customerLines) {
                $customer = $customerLines->first()?->invoice?->customer;
                $name = $customer?->display_name ?: 'Unassigned';
                $rows[] = $this->pad([$name], count($headers));
                $qty = '0.0000';
                $amount = '0.00';

                foreach ($customerLines as $line) {
                    $rows[] = [
                        '',
                        'Invoice',
                        $line->invoice?->invoice_date?->format('m/d/Y'),
                        $line->invoice?->invoice_number,
                        $customer?->bill_to_street1,
                        $customer?->bill_to_street2,
                        $customer?->bill_to_zip,
                        $this->nameAddress($customer),
                        $customer?->bill_to_street1,
                        $customer?->bill_to_city,
                        $customer?->bill_to_state,
                        $customer?->bill_to_zip,
                        $customer?->fax,
                        $this->itemCodeLabel($line),
                        $line->item?->income_account,
                        $this->n4($line->quantity),
                        $this->n2($line->rate),
                        $this->n2($line->amount),
                    ];
                    $qty = bcadd($qty, (string) $line->quantity, 4);
                    $amount = bcadd($amount, (string) $line->amount, 2);
                }

                $rows[] = [
                    'Total '.$name,
                    '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                    $this->n4($qty), '',
                    $this->n2($amount),
                ];
                $shipQty = bcadd($shipQty, $qty, 4);
                $shipAmount = bcadd($shipAmount, $amount, 2);
            }

            $rows[] = [
                'Total '.$shipLabel,
                '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                $this->n4($shipQty), '',
                $this->n2($shipAmount),
            ];
            $grandQty = bcadd($grandQty, $shipQty, 4);
            $grandAmount = bcadd($grandAmount, $shipAmount, 2);
        }

        if ($rows !== []) {
            $rows[] = [
                'TOTAL',
                '', '', '', '', '', '', '', '', '', '', '', '', '', '',
                $this->n4($grandQty), '',
                $this->n2($grandAmount),
            ];
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function repDetail(Collection $lines): array
    {
        $headers = ['', 'Type', 'Date', 'Num', 'Memo', 'Name', 'Item', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance'];
        $rows = [];
        $grandQty = '0.0000';
        $grandAmount = '0.00';
        $grandBalance = '0.00';

        $byRep = $lines->groupBy(fn (InvoiceLine $line) => $line->invoice?->createdBy?->name ?: 'No sales rep');

        foreach ($byRep as $rep => $repLines) {
            $rows[] = $this->pad([$rep], count($headers));
            $qty = '0.0000';
            $amount = '0.00';
            $balance = '0.00';

            foreach ($repLines as $line) {
                $share = $this->lineBalanceShare($line);
                $rows[] = [
                    '',
                    'Invoice',
                    $line->invoice?->invoice_date?->format('m/d/Y'),
                    $line->invoice?->invoice_number,
                    $line->invoice?->memo,
                    $line->invoice?->customer?->display_name,
                    $this->itemCodeLabel($line),
                    $this->n4($line->quantity),
                    $this->uom($line),
                    $this->n2($line->rate),
                    $this->n2($line->amount),
                    $share,
                ];
                $qty = bcadd($qty, (string) $line->quantity, 4);
                $amount = bcadd($amount, (string) $line->amount, 2);
                $balance = bcadd($balance, $share, 2);
            }

            $rows[] = [
                'Total '.$rep,
                '', '', '', '', '', '',
                $this->n4($qty), '', '',
                $this->n2($amount),
                $this->n2($balance),
            ];
            $grandQty = bcadd($grandQty, $qty, 4);
            $grandAmount = bcadd($grandAmount, $amount, 2);
            $grandBalance = bcadd($grandBalance, $balance, 2);
        }

        if ($rows !== []) {
            $rows[] = [
                'TOTAL',
                '', '', '', '', '', '',
                $this->n4($grandQty), '', '',
                $this->n2($grandAmount),
                $this->n2($grandBalance),
            ];
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function itemSummary(Collection $lines): array
    {
        $headers = ['', 'Qty', 'Amount', '% of Sales', 'Avg Price', 'COGS', 'Avg COGS', 'Gross Margin', 'Gross Margin %'];
        $rows = [];
        $grandQty = '0.0000';
        $grandAmount = '0.00';
        $grandCogs = '0.00';

        $totalSales = '0.00';
        foreach ($lines as $line) {
            $totalSales = bcadd($totalSales, (string) $line->amount, 2);
        }

        $byType = $lines->groupBy(fn (InvoiceLine $line) => $line->item?->itemType?->label
            ?: $line->item?->type
            ?: 'Inventory');

        foreach ($byType as $typeLabel => $typeLines) {
            $rows[] = $this->pad([$typeLabel], count($headers));
            $typeQty = '0.0000';
            $typeAmount = '0.00';
            $typeCogs = '0.00';

            foreach ($typeLines->groupBy(fn (InvoiceLine $line) => $line->item_id ?: 'none') as $itemLines) {
                $label = $this->itemLabel($itemLines->first());
                $qty = '0.0000';
                $amount = '0.00';
                $cogs = '0.00';
                foreach ($itemLines as $line) {
                    $qty = bcadd($qty, (string) $line->quantity, 4);
                    $amount = bcadd($amount, (string) $line->amount, 2);
                    $unitCost = (string) ($line->item?->average_cost ?: $line->item?->purchase_cost ?: 0);
                    $cogs = bcadd($cogs, bcmul($unitCost, (string) $line->quantity, 4), 2);
                }
                $avgPrice = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($amount, $qty, 4);
                $avgCogs = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($cogs, $qty, 4);
                $margin = bcsub($amount, $cogs, 2);
                $pctSales = bccomp($totalSales, '0', 2) === 0 ? '0' : bcdiv($amount, $totalSales, 6);
                $marginPct = bccomp($amount, '0', 2) === 0 ? '0' : bcdiv($margin, $amount, 6);

                $rows[] = [
                    $label,
                    $this->n4($qty),
                    $this->n2($amount),
                    $this->n4($pctSales),
                    $this->n2($avgPrice),
                    $this->n2($cogs),
                    $this->n2($avgCogs),
                    $this->n2($margin),
                    $this->n4($marginPct),
                ];

                $typeQty = bcadd($typeQty, $qty, 4);
                $typeAmount = bcadd($typeAmount, $amount, 2);
                $typeCogs = bcadd($typeCogs, $cogs, 2);
            }

            $rows[] = [
                'Total '.$typeLabel,
                $this->n4($typeQty),
                $this->n2($typeAmount),
                '', '',
                $this->n2($typeCogs),
                '',
                $this->n2(bcsub($typeAmount, $typeCogs, 2)),
                '',
            ];
            $grandQty = bcadd($grandQty, $typeQty, 4);
            $grandAmount = bcadd($grandAmount, $typeAmount, 2);
            $grandCogs = bcadd($grandCogs, $typeCogs, 2);
        }

        if ($rows !== []) {
            $rows[] = [
                'TOTAL',
                $this->n4($grandQty),
                $this->n2($grandAmount),
                '1',
                '',
                $this->n2($grandCogs),
                '',
                $this->n2(bcsub($grandAmount, $grandCogs, 2)),
                '',
            ];
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * @param  Collection<int, InvoiceLine>  $lines
     * @return array{headers: list<string>, rows: list<list<string|float|int|null>>}
     */
    protected function customerSummary(Collection $lines): array
    {
        $headers = ['', 'Qty', 'Amount', '% of Sales', 'Avg Price', 'COGS', 'Avg COGS', 'Gross Margin', 'Gross Margin %'];
        $rows = [];
        $grandQty = '0.0000';
        $grandAmount = '0.00';
        $grandCogs = '0.00';

        $totalSales = '0.00';
        foreach ($lines as $line) {
            $totalSales = bcadd($totalSales, (string) $line->amount, 2);
        }

        $byCustomer = $lines->sortBy(fn (InvoiceLine $line) => $line->invoice?->customer?->display_name ?? '')
            ->groupBy(fn (InvoiceLine $line) => $line->invoice?->customer_id ?: 'none');

        foreach ($byCustomer as $customerLines) {
            $name = $customerLines->first()?->invoice?->customer?->display_name ?: 'Unassigned';
            $qty = '0.0000';
            $amount = '0.00';
            $cogs = '0.00';
            foreach ($customerLines as $line) {
                $qty = bcadd($qty, (string) $line->quantity, 4);
                $amount = bcadd($amount, (string) $line->amount, 2);
                $unitCost = (string) ($line->item?->average_cost ?: $line->item?->purchase_cost ?: 0);
                $cogs = bcadd($cogs, bcmul($unitCost, (string) $line->quantity, 4), 2);
            }
            $avgPrice = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($amount, $qty, 4);
            $avgCogs = bccomp($qty, '0', 4) === 0 ? '0.00' : bcdiv($cogs, $qty, 4);
            $margin = bcsub($amount, $cogs, 2);
            $pctSales = bccomp($totalSales, '0', 2) === 0 ? '0' : bcdiv($amount, $totalSales, 6);
            $marginPct = bccomp($amount, '0', 2) === 0 ? '0' : bcdiv($margin, $amount, 6);

            $rows[] = [
                $name,
                $this->n4($qty),
                $this->n2($amount),
                $this->n4($pctSales),
                $this->n2($avgPrice),
                $this->n2($cogs),
                $this->n2($avgCogs),
                $this->n2($margin),
                $this->n4($marginPct),
            ];

            $grandQty = bcadd($grandQty, $qty, 4);
            $grandAmount = bcadd($grandAmount, $amount, 2);
            $grandCogs = bcadd($grandCogs, $cogs, 2);
        }

        if ($rows !== []) {
            $rows[] = [
                'TOTAL',
                $this->n4($grandQty),
                $this->n2($grandAmount),
                '1',
                '',
                $this->n2($grandCogs),
                '',
                $this->n2(bcsub($grandAmount, $grandCogs, 2)),
                '',
            ];
        }

        return ['headers' => $headers, 'rows' => $rows];
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
            $customer->bill_to_street1,
            $customer->bill_to_city,
            $customer->bill_to_state,
            $customer->bill_to_zip,
        ]));
    }

    /**
     * @param  list<string|float|int|null>  $cells
     * @return list<string|float|int|null>
     */
    protected function pad(array $cells, int $count): array
    {
        while (count($cells) < $count) {
            $cells[] = '';
        }

        return array_slice($cells, 0, $count);
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
