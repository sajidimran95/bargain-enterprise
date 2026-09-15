<?php

namespace Database\Seeders;

use App\Actions\Purchasing\ReceiveGoodsAction;
use App\Actions\Sales\CreateInvoiceAction;
use App\Actions\Sales\ReceivePaymentAction;
use App\Models\Account;
use App\Models\BankAccount;
use App\Models\Check;
use App\Models\CreditMemo;
use App\Models\CreditMemoLine;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\DepositItem;
use App\Models\Item;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Quote;
use App\Models\QuoteLine;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Models\SalesReceipt;
use App\Models\SalesReceiptLine;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Models\VendorBillLine;
use App\Models\VendorPayment;
use App\Models\VendorPaymentAllocation;
use App\Services\AccountingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class TransactionalDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (PurchaseOrder::query()->exists()) {
            return; // idempotent for re-seed without duplicates
        }

        $ownerId = User::query()->where('email', 'owner@bargain.local')->value('id');
        $customers = Customer::query()->active()->orderBy('id')->get();
        $vendors = Vendor::query()->active()->orderBy('id')->get();
        $items = Item::query()->active()->orderBy('id')->get();
        $undeposited = Account::query()->where('number', '1050')->first();
        $operatingBank = BankAccount::query()->where('name', 'Operating Account')->first();

        $this->seedPurchasing($vendors, $items, $ownerId);
        $this->seedQuotesAndOrders($customers, $items);
        $invoices = $this->seedInvoices($customers, $items, $ownerId);
        $this->seedPayments($invoices, $ownerId, $undeposited?->id);
        $this->seedCreditMemos($customers, $items);
        $this->seedSalesReceipts($customers, $items);
        $this->seedBanking($operatingBank, $ownerId);
    }

    protected function seedPurchasing($vendors, $items, ?int $userId): void
    {
        $receive = app(ReceiveGoodsAction::class);
        $accounting = app(AccountingService::class);

        for ($i = 1; $i <= 25; $i++) {
            $vendor = $vendors[($i - 1) % $vendors->count()];
            $date = Carbon::now()->subDays(20 + ($i * 7));
            $status = match ($i % 5) {
                0 => 'cancelled',
                1 => 'draft',
                default => 'open',
            };

            $po = PurchaseOrder::query()->create([
                'number' => 'PO-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'vendor_id' => $vendor->id,
                'order_date' => $date,
                'expected_date' => $date->copy()->addDays(7),
                'status' => $status,
                'subtotal' => 0,
                'total' => 0,
                'memo' => 'Demo purchase order',
            ]);

            $subtotal = '0.00';
            $lineItems = $items->random(min(4, $items->count()));
            foreach ($lineItems as $lineIndex => $item) {
                $qty = fake()->numberBetween(10, 40);
                $rate = (string) $item->purchase_cost;
                $amount = number_format($qty * (float) $rate, 2, '.', '');
                $subtotal = bcadd($subtotal, $amount, 2);
                PurchaseOrderLine::query()->create([
                    'purchase_order_id' => $po->id,
                    'item_id' => $item->id,
                    'description' => $item->name,
                    'quantity' => $qty,
                    'qty_received' => 0,
                    'rate' => $rate,
                    'amount' => $amount,
                ]);
            }
            $po->update(['subtotal' => $subtotal, 'total' => $subtotal]);

            if (in_array($status, ['cancelled', 'draft'], true)) {
                continue;
            }

            $po->load('lines');
            $receiptLines = [];
            foreach ($po->lines as $line) {
                $receiveQty = $i % 3 === 0 ? (float) bcdiv((string) $line->quantity, '2', 4) : (float) $line->quantity;
                $receiptLines[] = [
                    'item_id' => $line->item_id,
                    'purchase_order_line_id' => $line->id,
                    'quantity' => $receiveQty,
                    'unit_cost' => $line->rate,
                ];
            }

            $receipt = $receive->handle([
                'number' => 'GR-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'vendor_id' => $vendor->id,
                'purchase_order_id' => $po->id,
                'receipt_date' => $date->copy()->addDays(3),
                'created_by' => $userId,
            ], $receiptLines);

            $billTotal = '0.00';
            $bill = VendorBill::query()->create([
                'bill_number' => 'BILL-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'ref_no' => 'VREF-'.$i,
                'vendor_id' => $vendor->id,
                'goods_receipt_id' => $receipt->id,
                'bill_date' => $date->copy()->addDays(4),
                'due_date' => $date->copy()->addDays(34),
                'status' => 'open',
                'subtotal' => 0,
                'total' => 0,
                'amount_paid' => 0,
                'balance_due' => 0,
            ]);

            foreach ($receipt->lines as $rLine) {
                $amount = number_format((float) bcmul((string) $rLine->quantity, (string) $rLine->unit_cost, 4), 2, '.', '');
                $billTotal = bcadd($billTotal, $amount, 2);
                VendorBillLine::query()->create([
                    'vendor_bill_id' => $bill->id,
                    'item_id' => $rLine->item_id,
                    'description' => 'Received stock',
                    'quantity' => $rLine->quantity,
                    'rate' => $rLine->unit_cost,
                    'amount' => $amount,
                ]);
            }

            $bill->update([
                'subtotal' => $billTotal,
                'total' => $billTotal,
                'balance_due' => $billTotal,
            ]);

            $accounting->postBalancedEntry(
                'JE-BILL-'.$bill->bill_number,
                $bill->bill_date,
                [
                    ['account' => '1300', 'debit' => $billTotal, 'credit' => 0],
                    ['account' => '2000', 'debit' => 0, 'credit' => $billTotal],
                ],
                'Vendor bill '.$bill->bill_number,
                VendorBill::class,
                $bill->id,
                $userId
            );

            $vendor->balance = bcadd((string) $vendor->balance, $billTotal, 2);
            $vendor->save();

            if ($i % 2 === 0) {
                $payAmount = $i % 4 === 0 ? $billTotal : number_format((float) $billTotal / 2, 2, '.', '');
                $vp = VendorPayment::query()->create([
                    'payment_number' => 'VP-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                    'vendor_id' => $vendor->id,
                    'payment_date' => $date->copy()->addDays(20),
                    'amount' => $payAmount,
                    'method' => 'check',
                    'reference' => 'CHK-'.(4000 + $i),
                    'bank_account_id' => Account::query()->where('number', '1010')->value('id'),
                ]);
                VendorPaymentAllocation::query()->create([
                    'vendor_payment_id' => $vp->id,
                    'vendor_bill_id' => $bill->id,
                    'amount' => $payAmount,
                ]);
                $bill->amount_paid = $payAmount;
                $bill->balance_due = bcsub($billTotal, $payAmount, 2);
                $bill->status = bccomp((string) $bill->balance_due, '0', 2) === 0 ? 'paid' : 'partial';
                $bill->save();
                $vendor->balance = bcsub((string) $vendor->balance, $payAmount, 2);
                $vendor->save();

                $accounting->postBalancedEntry(
                    'JE-VP-'.$vp->payment_number,
                    $vp->payment_date,
                    [
                        ['account' => '2000', 'debit' => $payAmount, 'credit' => 0],
                        ['account' => '1010', 'debit' => 0, 'credit' => $payAmount],
                    ],
                    'Vendor payment '.$vp->payment_number,
                    VendorPayment::class,
                    $vp->id,
                    $userId
                );
            }
        }
    }

    protected function seedQuotesAndOrders($customers, $items): void
    {
        for ($i = 1; $i <= 25; $i++) {
            $customer = $customers[($i - 1) % $customers->count()];
            $date = Carbon::now()->subDays(10 + $i * 3);
            $status = ['draft', 'sent', 'accepted', 'rejected', 'converted'][$i % 5];
            $quote = Quote::query()->create([
                'number' => 'QT-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'quote_date' => $date,
                'expiry_date' => $date->copy()->addDays(30),
                'status' => $status,
                'subtotal' => 0,
                'tax_total' => 0,
                'total' => 0,
            ]);
            $subtotal = '0.00';
            foreach ($items->random(min(3, $items->count())) as $idx => $item) {
                $qty = fake()->numberBetween(2, 12);
                $rate = (string) $item->sales_price;
                $amount = number_format($qty * (float) $rate, 2, '.', '');
                $subtotal = bcadd($subtotal, $amount, 2);
                QuoteLine::query()->create([
                    'quote_id' => $quote->id,
                    'item_id' => $item->id,
                    'description' => $item->name,
                    'quantity' => $qty,
                    'rate' => $rate,
                    'amount' => $amount,
                    'taxable' => true,
                    'line_order' => $idx,
                ]);
            }
            $tax = number_format((float) $subtotal * 0.0635, 2, '.', '');
            $quote->update([
                'subtotal' => $subtotal,
                'tax_total' => $tax,
                'total' => bcadd($subtotal, $tax, 2),
            ]);

            $soStatus = ['draft', 'open', 'open', 'invoiced', 'cancelled'][$i % 5];
            $so = SalesOrder::query()->create([
                'number' => 'SO-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'quote_id' => $status === 'converted' ? $quote->id : null,
                'order_date' => $date->copy()->addDay(),
                'status' => $soStatus,
                'subtotal' => $subtotal,
                'tax_total' => $tax,
                'total' => bcadd($subtotal, $tax, 2),
            ]);
            foreach ($quote->lines as $line) {
                SalesOrderLine::query()->create([
                    'sales_order_id' => $so->id,
                    'item_id' => $line->item_id,
                    'description' => $line->description,
                    'quantity' => $line->quantity,
                    'rate' => $line->rate,
                    'amount' => $line->amount,
                    'taxable' => true,
                    'line_order' => $line->line_order,
                ]);
            }
        }
    }

    protected function seedInvoices($customers, $items, ?int $userId)
    {
        $createInvoice = app(CreateInvoiceAction::class);
        $created = collect();

        for ($i = 1; $i <= 80; $i++) {
            $customer = $customers[($i - 1) % $customers->count()];
            $date = Carbon::now()->subDays($i * 2)->startOfDay();
            $status = match (true) {
                $i % 17 === 0 => 'draft',
                default => 'open',
            };

            $selected = $items->random(min(fake()->numberBetween(2, 5), $items->count()));
            $lines = [];
            foreach ($selected as $item) {
                $lines[] = [
                    'item_id' => $item->id,
                    'quantity' => fake()->numberBetween(1, 8),
                    'rate' => $item->sales_price,
                    'taxable' => true,
                ];
            }

            $invoice = $createInvoice->handle([
                'customer_id' => $customer->id,
                'invoice_number' => 'INV-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'invoice_date' => $date->toDateString(),
                'due_date' => $date->copy()->addDays(15)->toDateString(),
                'tax_code_id' => $customer->tax_code_id,
                'status' => $status,
                'created_by' => $userId,
                'allow_negative_inventory' => true,
                'memo' => 'Demo invoice',
            ], $lines, postInventory: $status !== 'draft', postAccounting: $status !== 'draft');

            $created->push($invoice);
        }

        return $created;
    }

    protected function seedPayments($invoices, ?int $userId, ?int $undepositedId): void
    {
        $receive = app(ReceivePaymentAction::class);
        $open = $invoices->filter(fn ($inv) => $inv->status === 'open')->values();

        foreach ($open as $i => $invoice) {
            if ($i % 3 === 0) {
                continue; // leave unpaid
            }

            $payFull = $i % 2 === 0;
            $amount = $payFull
                ? (string) $invoice->balance_due
                : number_format((float) $invoice->balance_due * 0.4, 2, '.', '');

            if (bccomp($amount, '0', 2) <= 0) {
                continue;
            }

            $receive->handle([
                'customer_id' => $invoice->customer_id,
                'payment_number' => 'PMT-'.str_pad((string) ($i + 1), 5, '0', STR_PAD_LEFT),
                'payment_date' => Carbon::parse($invoice->invoice_date)->addDays(5)->toDateString(),
                'amount' => $amount,
                'method' => $i % 2 === 0 ? 'check' : 'cash',
                'reference' => 'REF-'.(7000 + $i),
                'deposit_to_account_id' => $undepositedId,
                'created_by' => $userId,
            ], [
                ['invoice_id' => $invoice->id, 'amount' => $amount],
            ]);
        }
    }

    protected function seedCreditMemos($customers, $items): void
    {
        for ($i = 1; $i <= 8; $i++) {
            $customer = $customers[$i % $customers->count()];
            $item = $items[$i % $items->count()];
            $qty = fake()->numberBetween(1, 3);
            $rate = (string) $item->sales_price;
            $amount = number_format($qty * (float) $rate, 2, '.', '');
            $tax = number_format((float) $amount * 0.0635, 2, '.', '');
            $total = bcadd($amount, $tax, 2);

            $memo = CreditMemo::query()->create([
                'credit_number' => 'CM-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'credit_date' => now()->subDays(15 + $i),
                'status' => 'open',
                'subtotal' => $amount,
                'tax_total' => $tax,
                'total' => $total,
                'remaining_credit' => $total,
                'memo' => 'Demo credit memo',
            ]);
            CreditMemoLine::query()->create([
                'credit_memo_id' => $memo->id,
                'item_id' => $item->id,
                'description' => $item->name,
                'quantity' => $qty,
                'rate' => $rate,
                'amount' => $amount,
                'taxable' => true,
            ]);
        }
    }

    protected function seedSalesReceipts($customers, $items): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $customer = $customers[$i % $customers->count()];
            $item = $items->random();
            $qty = fake()->numberBetween(1, 4);
            $amount = number_format($qty * (float) $item->sales_price, 2, '.', '');
            $tax = number_format((float) $amount * 0.0635, 2, '.', '');
            $receipt = SalesReceipt::query()->create([
                'number' => 'SR-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'receipt_date' => now()->subDays($i),
                'subtotal' => $amount,
                'tax_total' => $tax,
                'total' => bcadd($amount, $tax, 2),
                'payment_method' => 'cash',
            ]);
            SalesReceiptLine::query()->create([
                'sales_receipt_id' => $receipt->id,
                'item_id' => $item->id,
                'description' => $item->name,
                'quantity' => $qty,
                'rate' => $item->sales_price,
                'amount' => $amount,
                'taxable' => true,
            ]);
        }
    }

    protected function seedBanking(?BankAccount $bank, ?int $userId): void
    {
        if (! $bank) {
            return;
        }

        $undeposited = Payment::query()->where('deposited', false)->orderBy('id')->take(15)->get();
        if ($undeposited->isEmpty()) {
            return;
        }

        $chunks = $undeposited->chunk(5);
        $n = 1;
        foreach ($chunks as $chunk) {
            $total = $chunk->sum(fn ($p) => (float) $p->amount);
            $deposit = Deposit::query()->create([
                'number' => 'DEP-'.str_pad((string) $n, 5, '0', STR_PAD_LEFT),
                'bank_account_id' => $bank->id,
                'deposit_date' => now()->subDays(3 * $n),
                'total' => number_format($total, 2, '.', ''),
                'memo' => 'Batch deposit',
            ]);

            foreach ($chunk as $payment) {
                DepositItem::query()->create([
                    'deposit_id' => $deposit->id,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                ]);
                $payment->update(['deposited' => true]);
            }

            app(AccountingService::class)->postBalancedEntry(
                'JE-DEP-'.$deposit->number,
                $deposit->deposit_date,
                [
                    ['account' => '1010', 'debit' => $deposit->total, 'credit' => 0],
                    ['account' => '1050', 'debit' => 0, 'credit' => $deposit->total],
                ],
                'Deposit '.$deposit->number,
                Deposit::class,
                $deposit->id,
                $userId
            );
            $n++;
        }

        Check::query()->updateOrCreate(
            ['check_number' => '1001'],
            [
                'bank_account_id' => $bank->id,
                'vendor_id' => Vendor::query()->active()->value('id'),
                'check_date' => now()->subDays(12),
                'amount' => 250.00,
                'payee' => 'Office Supplies Demo',
                'memo' => 'Demo check',
            ]
        );
    }
}
