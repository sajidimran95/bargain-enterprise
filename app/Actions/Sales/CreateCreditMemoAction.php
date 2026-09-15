<?php

namespace App\Actions\Sales;

use App\Models\CreditMemo;
use App\Models\CreditMemoLine;
use App\Models\Customer;
use App\Models\Item;
use App\Services\AccountingService;
use App\Services\AuditLogger;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateCreditMemoAction
{
    public function __construct(
        protected InventoryService $inventory,
        protected AccountingService $accounting,
        protected AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function handle(array $header, array $lines): CreditMemo
    {
        $header = Validator::make($header, [
            'credit_number' => ['required', 'string', 'unique:credit_memos,credit_number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'credit_date' => ['required', 'date'],
            'memo' => ['nullable', 'string'],
            'class' => ['nullable', 'string', 'max:100'],
            'template' => ['nullable', 'string', 'max:100'],
            'po_number' => ['nullable', 'string', 'max:50'],
            'print_later' => ['sometimes', 'boolean'],
            'email_later' => ['sometimes', 'boolean'],
            'is_pending' => ['sometimes', 'boolean'],
            'created_by' => ['nullable', 'exists:users,id'],
            'post_accounting' => ['sometimes', 'boolean'],
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

            $isPending = (bool) ($header['is_pending'] ?? false);

            $memo = CreditMemo::query()->create([
                'credit_number' => $header['credit_number'],
                'customer_id' => $customer->id,
                'credit_date' => $header['credit_date'],
                'status' => $isPending ? 'pending' : 'open',
                'subtotal' => $subtotal,
                'tax_total' => 0,
                'total' => $subtotal,
                'remaining_credit' => $isPending ? 0 : $subtotal,
                'memo' => $header['memo'] ?? null,
                'class' => $header['class'] ?? null,
                'template' => $header['template'] ?? null,
                'po_number' => $header['po_number'] ?? null,
                'print_later' => (bool) ($header['print_later'] ?? false),
                'email_later' => (bool) ($header['email_later'] ?? false),
                'is_pending' => $isPending,
            ]);

            foreach ($prepared as $line) {
                CreditMemoLine::query()->create([
                    'credit_memo_id' => $memo->id,
                    'item_id' => $line['item']->id,
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'rate' => $line['rate'],
                    'amount' => $line['amount'],
                    'taxable' => $line['taxable'],
                ]);

                if (! $isPending && $line['item']->tracksInventory()) {
                    $this->inventory->post($line['item'], [
                        'type' => 'credit_memo',
                        'qty_in' => $line['quantity'],
                        'unit_cost' => $line['item']->average_cost,
                        'reference_type' => CreditMemo::class,
                        'reference_id' => $memo->id,
                        'occurred_at' => $header['credit_date'],
                        'created_by' => $header['created_by'] ?? auth()->id(),
                        'memo' => 'Credit memo '.$memo->credit_number,
                    ]);
                }
            }

            if (! $isPending) {
                $customer->balance = bcsub((string) $customer->balance, $subtotal, 2);
                $customer->save();
            }

            if (! $isPending && ($header['post_accounting'] ?? true)) {
                // Reverse sales into AR credit: Dr Sales / Cr AR
                $this->accounting->postBalancedEntry(
                    'JE-CM-'.$memo->credit_number,
                    $header['credit_date'],
                    [
                        ['account' => '4000', 'debit' => $subtotal, 'credit' => 0, 'memo' => 'Sales returns'],
                        ['account' => '1200', 'debit' => 0, 'credit' => $subtotal, 'memo' => 'AR credit'],
                    ],
                    'Credit memo '.$memo->credit_number,
                    CreditMemo::class,
                    $memo->id,
                    $header['created_by'] ?? auth()->id()
                );
            }

            $memo = $memo->load('lines');

            $this->audit->record('created', $memo, null, [
                'credit_number' => $memo->credit_number,
                'customer_id' => $memo->customer_id,
                'total' => $memo->total,
                'remaining_credit' => $memo->remaining_credit,
                'status' => $memo->status,
            ]);

            return $memo;
        });
    }
}
