<?php

namespace App\Actions\Sales;

use App\Models\CreditMemo;
use App\Models\CreditMemoLine;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use App\Models\JournalEntry;
use App\Models\TaxCode;
use App\Services\AccountingService;
use App\Services\AuditLogger;
use App\Services\InventoryService;
use App\Support\DocumentNumbers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class UpdateInvoiceAction
{
    public function __construct(
        protected InventoryService $inventory,
        protected AccountingService $accounting,
        protected AuditLogger $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  array<int, array<string, mixed>>  $lines
     * @return array{invoice: Invoice, overpayment_credit: ?CreditMemo, amount_still_due: string, overpayment: string}
     */
    public function handle(Invoice $invoice, array $header, array $lines, bool $postInventory = true, bool $postAccounting = true): array
    {
        $header = Validator::make($header, [
            'customer_id' => ['required', 'exists:customers,id'],
            'invoice_number' => [
                'required',
                'string',
                Rule::unique('invoices', 'invoice_number')->ignore($invoice->id),
            ],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'tax_code_id' => ['nullable', 'exists:tax_codes,id'],
            'status' => ['nullable', 'string'],
            'customer_message' => ['nullable', 'string'],
            'memo' => ['nullable', 'string'],
            'class' => ['nullable', 'string', 'max:100'],
            'template' => ['nullable', 'string', 'max:100'],
            'print_later' => ['sometimes', 'boolean'],
            'email_later' => ['sometimes', 'boolean'],
            'is_pending' => ['sometimes', 'boolean'],
            'updated_by' => ['nullable', 'exists:users,id'],
            'allow_negative_inventory' => ['sometimes', 'boolean'],
        ])->validate();

        if ($lines === []) {
            throw ValidationException::withMessages(['lines' => 'At least one line is required.']);
        }

        return DB::transaction(function () use ($invoice, $header, $lines, $postInventory, $postAccounting) {
            $invoice = Invoice::query()->whereKey($invoice->id)->lockForUpdate()->with('lines.item')->firstOrFail();

            if (in_array($invoice->status, ['void', 'voided'], true)) {
                throw new RuntimeException('Cannot edit a voided invoice.');
            }

            $customer = Customer::query()->whereKey($header['customer_id'])->lockForUpdate()->firstOrFail();
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
            $wasNonPosting = in_array($invoice->status, ['draft', 'pending'], true) || $invoice->is_pending;
            $isNonPosting = $isPending || in_array($header['status'] ?? '', ['draft', 'pending'], true);

            $oldBalanceDue = (string) $invoice->balance_due;
            $oldAmountPaid = (string) $invoice->amount_paid;
            $oldTotal = (string) $invoice->total;
            $oldLines = $invoice->lines->map(fn (InvoiceLine $line) => [
                'item' => $line->item,
                'quantity' => $line->quantity,
            ])->all();

            if ($postInventory) {
                $oldForStock = $wasNonPosting ? [] : $oldLines;
                $newForStock = $isNonPosting ? [] : $prepared;
                $this->inventory->syncSaleQuantities(
                    $oldForStock,
                    $newForStock,
                    Invoice::class,
                    (int) $invoice->id,
                    [
                        'type' => 'invoice_edit',
                        'occurred_at' => $header['invoice_date'],
                        'created_by' => $header['updated_by'] ?? auth()->id(),
                        'allow_negative' => (bool) ($header['allow_negative_inventory'] ?? false),
                        'memo' => 'Invoice edit '.$invoice->invoice_number,
                    ]
                );
            }

            $invoice->lines()->delete();
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
            }

            $overpayment = '0.00';
            $overpaymentCredit = null;
            $amountPaid = $oldAmountPaid;

            if ($isNonPosting) {
                $amountPaid = '0.00';
                $balanceDue = '0.00';
                $status = 'pending';
            } else {
                if (bccomp($amountPaid, $total, 2) > 0) {
                    $overpayment = bcsub($amountPaid, $total, 2);
                    $amountPaid = $total;
                    $balanceDue = '0.00';
                    $status = 'paid';
                } else {
                    $balanceDue = bcsub($total, $amountPaid, 2);
                    $status = bccomp($balanceDue, '0', 2) === 0
                        ? 'paid'
                        : (bccomp($amountPaid, '0', 2) > 0 ? 'partial' : 'open');
                }
            }

            $invoice->fill([
                'invoice_number' => $header['invoice_number'],
                'customer_id' => $customer->id,
                'tax_code_id' => $taxCode?->id,
                'invoice_date' => $header['invoice_date'],
                'due_date' => $header['due_date'] ?? null,
                'status' => $status,
                'subtotal' => $subtotal,
                'tax_total' => $taxTotal,
                'total' => $total,
                'amount_paid' => $amountPaid,
                'balance_due' => $balanceDue,
                'customer_message' => $header['customer_message'] ?? null,
                'memo' => $header['memo'] ?? null,
                'class' => $header['class'] ?? null,
                'template' => $header['template'] ?? null,
                'print_later' => (bool) ($header['print_later'] ?? false),
                'email_later' => (bool) ($header['email_later'] ?? false),
                'is_pending' => $isPending,
                'updated_by' => $header['updated_by'] ?? auth()->id(),
            ]);
            $invoice->save();

            if ($postAccounting) {
                $arDelta = bcsub((string) $invoice->balance_due, $wasNonPosting ? '0.00' : $oldBalanceDue, 2);

                if (! $isNonPosting && ! $wasNonPosting && bccomp($total, $oldTotal, 2) !== 0) {
                    $this->postTotalAdjustmentEntry($invoice, $oldTotal, $total, $header);
                } elseif ($wasNonPosting && ! $isNonPosting) {
                    $this->postFullOpenEntry($invoice, $prepared, $subtotal, $taxTotal, $total, $header);
                    $arDelta = (string) $invoice->balance_due;
                } elseif (! $wasNonPosting && $isNonPosting) {
                    $this->postCloseOpenArEntry($invoice, $oldBalanceDue, $header);
                    $arDelta = bcmul($oldBalanceDue, '-1', 2);
                }

                if (bccomp($arDelta, '0', 2) !== 0) {
                    $customer->balance = bcadd((string) $customer->balance, $arDelta, 2);
                    $customer->save();
                }
            }

            if (bccomp($overpayment, '0', 2) > 0) {
                $overpaymentCredit = $this->createOverpaymentCredit(
                    $customer,
                    $invoice,
                    $overpayment,
                    $header['updated_by'] ?? auth()->id()
                );
            }

            $invoice = $invoice->load('lines.item');

            $this->audit->record('updated', $invoice, [
                'total' => $oldTotal,
                'amount_paid' => $oldAmountPaid,
                'balance_due' => $oldBalanceDue,
            ], [
                'invoice_number' => $invoice->invoice_number,
                'status' => $invoice->status,
                'total' => $invoice->total,
                'amount_paid' => $invoice->amount_paid,
                'balance_due' => $invoice->balance_due,
                'overpayment' => $overpayment,
            ], $header['updated_by'] ?? auth()->id());

            return [
                'invoice' => $invoice,
                'overpayment_credit' => $overpaymentCredit,
                'amount_still_due' => (string) $invoice->balance_due,
                'overpayment' => $overpayment,
            ];
        });
    }

    /**
     * @param  array<int, array{item: Item, quantity: string}>  $prepared
     * @param  array<string, mixed>  $header
     */
    protected function postFullOpenEntry(
        Invoice $invoice,
        array $prepared,
        string $subtotal,
        string $taxTotal,
        string $total,
        array $header,
    ): void {
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
            'JE-INV-EDIT-'.$invoice->invoice_number.'-'.now()->format('His'),
            $header['invoice_date'],
            $linesJournal,
            'Invoice edit open '.$invoice->invoice_number,
            Invoice::class,
            $invoice->id,
            $header['updated_by'] ?? auth()->id()
        );
    }

    /**
     * @param  array<string, mixed>  $header
     */
    protected function postCloseOpenArEntry(Invoice $invoice, string $oldBalanceDue, array $header): void
    {
        if (bccomp($oldBalanceDue, '0', 2) <= 0) {
            return;
        }

        $this->accounting->postBalancedEntry(
            'JE-INV-PEND-'.$invoice->invoice_number.'-'.now()->format('His'),
            $header['invoice_date'],
            [
                ['account' => '4000', 'debit' => $oldBalanceDue, 'credit' => 0, 'memo' => 'Reverse sales to pending'],
                ['account' => '1200', 'debit' => 0, 'credit' => $oldBalanceDue, 'memo' => 'Reverse AR'],
            ],
            'Invoice marked pending '.$invoice->invoice_number,
            Invoice::class,
            $invoice->id,
            $header['updated_by'] ?? auth()->id()
        );
    }

    /**
     * @param  array<string, mixed>  $header
     */
    protected function postTotalAdjustmentEntry(Invoice $invoice, string $oldTotal, string $newTotal, array $header): void
    {
        $delta = bcsub($newTotal, $oldTotal, 2);
        if (bccomp($delta, '0', 2) === 0) {
            return;
        }

        if (bccomp($delta, '0', 2) > 0) {
            $lines = [
                ['account' => '1200', 'debit' => $delta, 'credit' => 0, 'memo' => 'AR increase'],
                ['account' => '4000', 'debit' => 0, 'credit' => $delta, 'memo' => 'Sales increase'],
            ];
        } else {
            $abs = bcmul($delta, '-1', 2);
            $lines = [
                ['account' => '4000', 'debit' => $abs, 'credit' => 0, 'memo' => 'Sales decrease'],
                ['account' => '1200', 'debit' => 0, 'credit' => $abs, 'memo' => 'AR decrease'],
            ];
        }

        $suffix = JournalEntry::query()
            ->where('reference_type', Invoice::class)
            ->where('reference_id', $invoice->id)
            ->count() + 1;

        $this->accounting->postBalancedEntry(
            'JE-INV-ADJ-'.$invoice->invoice_number.'-'.$suffix,
            $header['invoice_date'],
            $lines,
            'Invoice amount adjustment '.$invoice->invoice_number,
            Invoice::class,
            $invoice->id,
            $header['updated_by'] ?? auth()->id()
        );
    }

    protected function createOverpaymentCredit(
        Customer $customer,
        Invoice $invoice,
        string $amount,
        ?int $userId,
    ): CreditMemo {
        $item = Item::query()
            ->where(function ($q) {
                $q->where('type', 'service')
                    ->orWhere('type', 'non_inventory')
                    ->orWhere('type', 'other_charge');
            })
            ->orderBy('id')
            ->first()
            ?? Item::query()->orderBy('id')->firstOrFail();

        $credit = CreditMemo::query()->create([
            'credit_number' => DocumentNumbers::next(CreditMemo::class, 'credit_number', 'CM-'),
            'customer_id' => $customer->id,
            'credit_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => $amount,
            'tax_total' => 0,
            'total' => $amount,
            'remaining_credit' => $amount,
            'memo' => 'Overpayment from invoice '.$invoice->invoice_number.' edit',
            'is_pending' => false,
        ]);

        CreditMemoLine::query()->create([
            'credit_memo_id' => $credit->id,
            'item_id' => $item->id,
            'description' => 'Overpayment credit from '.$invoice->invoice_number,
            'quantity' => 1,
            'rate' => $amount,
            'amount' => $amount,
            'taxable' => false,
        ]);

        // Money-only credit: do not restock. Customer balance reflects credit available.
        $customer->balance = bcsub((string) $customer->balance, $amount, 2);
        $customer->save();

        $this->audit->record('created', $credit, null, [
            'credit_number' => $credit->credit_number,
            'total' => $credit->total,
            'from_invoice' => $invoice->invoice_number,
            'overpayment' => $amount,
        ], $userId);

        return $credit;
    }
}
