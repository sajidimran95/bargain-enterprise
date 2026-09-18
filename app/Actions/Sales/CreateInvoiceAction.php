<?php

namespace App\Actions\Sales;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use App\Models\TaxCode;
use App\Services\AccountingService;
use App\Services\AuditLogger;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateInvoiceAction
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
    public function handle(array $header, array $lines, bool $postInventory = true, bool $postAccounting = true): Invoice
    {
        $header = Validator::make($header, [
            'customer_id' => ['required', 'exists:customers,id'],
            'invoice_number' => ['required', 'string', 'unique:invoices,invoice_number'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'tax_code_id' => ['nullable', 'exists:tax_codes,id'],
            'sales_order_id' => ['nullable', 'exists:sales_orders,id'],
            'status' => ['nullable', 'string'],
            'customer_message' => ['nullable', 'string'],
            'memo' => ['nullable', 'string'],
            'class' => ['nullable', 'string', 'max:100'],
            'template' => ['nullable', 'string', 'max:100'],
            'print_later' => ['sometimes', 'boolean'],
            'email_later' => ['sometimes', 'boolean'],
            'is_pending' => ['sometimes', 'boolean'],
            'created_by' => ['nullable', 'exists:users,id'],
            'allow_negative_inventory' => ['sometimes', 'boolean'],
        ])->validate();

        if ($lines === []) {
            throw ValidationException::withMessages(['lines' => 'At least one line is required.']);
        }

        return DB::transaction(function () use ($header, $lines, $postInventory, $postAccounting) {
            $customer = Customer::query()->findOrFail($header['customer_id']);
            $taxCode = isset($header['tax_code_id'])
                ? TaxCode::query()->find($header['tax_code_id'])
                : $customer->taxCode;
            $taxRate = $taxCode?->rate ?? 0;

            $subtotal = '0.00';
            $taxTotal = '0.00';
            $prepared = [];

            foreach ($lines as $index => $line) {
                $item = Item::query()->findOrFail($line['item_id']);
                $qty = number_format((float) $line['quantity'], 4, '.', '');
                $rate = number_format((float) ($line['rate'] ?? $item->sales_price), 2, '.', '');
                $amount = number_format((float) bcmul($qty, $rate, 4), 2, '.', '');
                $taxable = (bool) ($line['taxable'] ?? true);
                $taxAmount = $taxable
                    ? number_format((float) bcmul($amount, bcdiv((string) $taxRate, '100', 6), 6), 2, '.', '')
                    : '0.00';

                $subtotal = bcadd($subtotal, $amount, 2);
                $taxTotal = bcadd($taxTotal, $taxAmount, 2);

                $prepared[] = [
                    'item' => $item,
                    'description' => $line['description'] ?? ($item->sales_description ?: $item->name),
                    'quantity' => $qty,
                    'rate' => $rate,
                    'amount' => $amount,
                    'taxable' => $taxable,
                    'tax_amount' => $taxAmount,
                    'line_order' => $index,
                ];
            }

            $total = bcadd($subtotal, $taxTotal, 2);
            $isPending = (bool) ($header['is_pending'] ?? false);
            $status = $isPending ? 'pending' : ($header['status'] ?? 'open');
            $isNonPosting = in_array($status, ['draft', 'pending'], true);

            $invoice = Invoice::query()->create([
                'invoice_number' => $header['invoice_number'],
                'customer_id' => $customer->id,
                'sales_order_id' => $header['sales_order_id'] ?? null,
                'tax_code_id' => $taxCode?->id,
                'invoice_date' => $header['invoice_date'],
                'due_date' => $header['due_date'] ?? null,
                'status' => $status,
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total' => $total,
                'amount_paid' => 0,
                'balance_due' => $isNonPosting ? 0 : $total,
                'customer_message' => $header['customer_message'] ?? null,
                'memo' => $header['memo'] ?? null,
                'class' => $header['class'] ?? null,
                'template' => $header['template'] ?? null,
                'print_later' => (bool) ($header['print_later'] ?? false),
                'email_later' => (bool) ($header['email_later'] ?? false),
                'is_pending' => $isPending,
                'created_by' => $header['created_by'] ?? auth()->id(),
                'updated_by' => $header['created_by'] ?? auth()->id(),
            ]);

            foreach ($prepared as $line) {
                InvoiceLine::query()->create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $line['item']->id,
                    'description' => $line['description'],
                    'quantity' => $line['quantity'],
                    'rate' => $line['rate'],
                    'amount' => $line['amount'],
                    'taxable' => $line['taxable'],
                    'tax_amount' => $line['tax_amount'],
                    'line_order' => $line['line_order'],
                ]);

                if ($postInventory && ! $isNonPosting && $line['item']->tracksInventory()) {
                    $this->inventory->post($line['item'], [
                        'type' => 'sale',
                        'qty_out' => $line['quantity'],
                        'unit_cost' => $line['item']->average_cost,
                        'reference_type' => Invoice::class,
                        'reference_id' => $invoice->id,
                        'occurred_at' => $header['invoice_date'],
                        'created_by' => $header['created_by'] ?? auth()->id(),
                        'allow_negative' => (bool) ($header['allow_negative_inventory'] ?? false),
                        'memo' => 'Invoice '.$invoice->invoice_number,
                    ]);
                }
            }

            if ($postAccounting && ! $isNonPosting) {
                $cogs = '0.00';
                foreach ($prepared as $line) {
                    if (! $line['item']->tracksInventory()) {
                        continue;
                    }
                    $cogs = bcadd($cogs, bcmul($line['quantity'], (string) $line['item']->average_cost, 4), 2);
                }

                $linesJournal = [
                    ['account' => '1200', 'debit' => $total, 'credit' => 0, 'memo' => 'AR'],
                    ['account' => '4000', 'debit' => 0, 'credit' => $subtotal, 'memo' => 'Sales'],
                ];
                if (bccomp($taxTotal, '0', 2) > 0) {
                    $linesJournal[] = ['account' => '2200', 'debit' => 0, 'credit' => $taxTotal, 'memo' => 'Sales Tax'];
                }
                if (bccomp($cogs, '0', 2) > 0) {
                    $linesJournal[] = ['account' => '5000', 'debit' => $cogs, 'credit' => 0, 'memo' => 'COGS'];
                    $linesJournal[] = ['account' => '1300', 'debit' => 0, 'credit' => $cogs, 'memo' => 'Inventory'];
                }

                $this->accounting->postBalancedEntry(
                    'JE-INV-'.$invoice->invoice_number,
                    $header['invoice_date'],
                    $linesJournal,
                    'Invoice '.$invoice->invoice_number,
                    Invoice::class,
                    $invoice->id,
                    $header['created_by'] ?? auth()->id()
                );

                $customer->balance = bcadd((string) $customer->balance, $total, 2);
                $customer->save();
            }

            $invoice = $invoice->load('lines');

            $this->audit->record('created', $invoice, null, [
                'invoice_number' => $invoice->invoice_number,
                'customer_id' => $invoice->customer_id,
                'status' => $invoice->status,
                'total' => $invoice->total,
                'balance_due' => $invoice->balance_due,
            ], $header['created_by'] ?? auth()->id());

            return $invoice;
        });
    }
}
