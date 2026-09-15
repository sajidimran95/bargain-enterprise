<?php

namespace App\Actions\Sales;

use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesReceipt;
use App\Models\SalesReceiptLine;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateSalesReceiptAction
{
    public function __construct(protected InventoryService $inventory) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function handle(array $header, array $lines): SalesReceipt
    {
        $header = Validator::make($header, [
            'number' => ['required', 'string', 'unique:sales_receipts,number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'receipt_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:50'],
            'memo' => ['nullable', 'string'],
            'created_by' => ['nullable', 'exists:users,id'],
            'allow_negative_inventory' => ['sometimes', 'boolean'],
        ])->validate();

        if ($lines === []) {
            throw ValidationException::withMessages(['lines' => 'At least one line is required.']);
        }

        return DB::transaction(function () use ($header, $lines) {
            $customer = Customer::query()->findOrFail($header['customer_id']);
            $subtotal = '0.00';
            $prepared = [];

            foreach ($lines as $index => $line) {
                $item = Item::query()->findOrFail($line['item_id']);
                $qty = number_format((float) $line['quantity'], 4, '.', '');
                $rate = number_format((float) ($line['rate'] ?? $item->sales_price), 2, '.', '');
                $amount = number_format((float) bcmul($qty, $rate, 4), 2, '.', '');
                $subtotal = bcadd($subtotal, $amount, 2);

                $prepared[] = [
                    'item' => $item,
                    'description' => $line['description'] ?? ($item->sales_description ?: $item->name),
                    'quantity' => $qty,
                    'rate' => $rate,
                    'amount' => $amount,
                    'taxable' => (bool) ($line['taxable'] ?? true),
                    'line_order' => $index,
                ];
            }

            $receipt = SalesReceipt::query()->create([
                'number' => $header['number'],
                'customer_id' => $customer->id,
                'receipt_date' => $header['receipt_date'],
                'subtotal' => $subtotal,
                'tax_total' => 0,
                'total' => $subtotal,
                'payment_method' => $header['payment_method'],
                'memo' => $header['memo'] ?? null,
            ]);

            foreach ($prepared as $line) {
                SalesReceiptLine::query()->create([
                    'sales_receipt_id' => $receipt->id,
                    'item_id' => $line['item']->id,
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'rate' => $line['rate'],
                    'amount' => $line['amount'],
                    'taxable' => $line['taxable'],
                ]);

                if ($line['item']->tracksInventory()) {
                    $this->inventory->post($line['item'], [
                        'type' => 'sale',
                        'qty_out' => $line['quantity'],
                        'unit_cost' => $line['item']->average_cost,
                        'reference_type' => SalesReceipt::class,
                        'reference_id' => $receipt->id,
                        'occurred_at' => $header['receipt_date'],
                        'created_by' => $header['created_by'] ?? auth()->id(),
                        'allow_negative' => (bool) ($header['allow_negative_inventory'] ?? false),
                        'memo' => 'Sales receipt '.$receipt->number,
                    ]);
                }
            }

            return $receipt->load('lines');
        });
    }
}
