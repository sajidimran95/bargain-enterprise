<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('number', 20)->unique();
            $table->string('name');
            $table->string('type'); // asset, liability, equity, income, expense, cogs
            $table->string('subtype')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('price_level_id')->constrained()->cascadeOnDelete();
            $table->decimal('price', 15, 2);
            $table->timestamps();
            $table->unique(['item_id', 'price_level_id']);
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // opening_stock, purchase, sale, return, adjustment, transfer
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('qty_in', 15, 4)->default(0);
            $table->decimal('qty_out', 15, 4)->default(0);
            $table->decimal('unit_cost', 15, 4)->default(0);
            $table->decimal('balance_after', 15, 4);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('memo')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'created_at']);
            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->string('memo')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts');
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->date('quote_date');
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('draft'); // draft, sent, accepted, rejected, converted
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('memo')->nullable();
            $table->foreignId('converted_invoice_id')->nullable();
            $table->timestamps();
        });

        Schema::create('quote_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->boolean('taxable')->default(true);
            $table->unsignedInteger('line_order')->default(0);
            $table->timestamps();
        });

        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('quote_id')->nullable()->constrained()->nullOnDelete();
            $table->date('order_date');
            $table->string('status')->default('open'); // draft, open, invoiced, cancelled
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->boolean('taxable')->default(true);
            $table->unsignedInteger('line_order')->default(0);
            $table->timestamps();
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('sales_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tax_code_id')->nullable()->constrained()->nullOnDelete();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->string('status')->default('open'); // draft, open, partial, paid, void
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->string('customer_message')->nullable();
            $table->text('memo')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['customer_id', 'invoice_date']);
            $table->index('invoice_date');
        });

        Schema::table('quotes', function (Blueprint $table) {
            $table->foreign('converted_invoice_id')->references('id')->on('invoices')->nullOnDelete();
        });

        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->boolean('taxable')->default(true);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->unsignedInteger('line_order')->default(0);
            $table->timestamps();
        });

        Schema::create('sales_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->date('receipt_date');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->text('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_receipt_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->boolean('taxable')->default(true);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->decimal('unapplied_amount', 15, 2)->default(0);
            $table->string('method')->default('check'); // check, cash, card, ach
            $table->string('reference')->nullable();
            $table->foreignId('deposit_to_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->boolean('deposited')->default(false);
            $table->text('memo')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['customer_id', 'payment_date']);
        });

        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            $table->unique(['payment_id', 'invoice_id']);
        });

        Schema::create('credit_memos', function (Blueprint $table) {
            $table->id();
            $table->string('credit_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->date('credit_date');
            $table->string('status')->default('open'); // open, applied, refunded
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('remaining_credit', 15, 2)->default(0);
            $table->text('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('credit_memo_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_memo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->boolean('taxable')->default(true);
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('vendor_id')->constrained();
            $table->date('order_date');
            $table->date('expected_date')->nullable();
            $table->string('status')->default('open'); // draft, open, partial, received, cancelled
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->text('memo')->nullable();
            $table->timestamps();
            $table->index('vendor_id');
        });

        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 4);
            $table->decimal('qty_received', 15, 4)->default(0);
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('purchase_order_id')->nullable()->constrained()->nullOnDelete();
            $table->date('receipt_date');
            $table->text('memo')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('goods_receipt_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receipt_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('purchase_order_line_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('quantity', 15, 4);
            $table->decimal('unit_cost', 15, 4);
            $table->timestamps();
        });

        Schema::create('vendor_bills', function (Blueprint $table) {
            $table->id();
            $table->string('bill_number')->unique();
            $table->string('ref_no')->nullable();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('goods_receipt_id')->nullable()->constrained()->nullOnDelete();
            $table->date('bill_date');
            $table->date('due_date')->nullable();
            $table->string('status')->default('open'); // open, partial, paid
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2)->default(0);
            $table->text('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_bill_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_bill_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('description')->nullable();
            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('vendor_id')->constrained();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->string('method')->default('check');
            $table->string('reference')->nullable();
            $table->foreignId('bank_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->text('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_bill_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->string('name');
            $table->string('bank_name')->nullable();
            $table->string('account_number_mask')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->foreignId('bank_account_id')->constrained();
            $table->date('deposit_date');
            $table->decimal('total', 15, 2);
            $table->text('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('deposit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });

        Schema::create('checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_number')->unique();
            $table->foreignId('bank_account_id')->constrained();
            $table->foreignId('vendor_id')->nullable()->constrained()->nullOnDelete();
            $table->date('check_date');
            $table->decimal('amount', 15, 2);
            $table->string('payee')->nullable();
            $table->text('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->timestamps();
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('checks');
        Schema::dropIfExists('deposit_items');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('vendor_payment_allocations');
        Schema::dropIfExists('vendor_payments');
        Schema::dropIfExists('vendor_bill_lines');
        Schema::dropIfExists('vendor_bills');
        Schema::dropIfExists('goods_receipt_lines');
        Schema::dropIfExists('goods_receipts');
        Schema::dropIfExists('purchase_order_lines');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('credit_memo_lines');
        Schema::dropIfExists('credit_memos');
        Schema::dropIfExists('payment_allocations');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('sales_receipt_lines');
        Schema::dropIfExists('sales_receipts');
        Schema::dropIfExists('invoice_lines');
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('converted_invoice_id');
        });
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('sales_order_lines');
        Schema::dropIfExists('sales_orders');
        Schema::dropIfExists('quote_lines');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('journal_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('item_prices');
        Schema::dropIfExists('accounts');
    }
};
