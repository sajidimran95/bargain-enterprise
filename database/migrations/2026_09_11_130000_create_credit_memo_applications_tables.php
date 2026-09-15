<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credit_memo_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_memo_id')->constrained('credit_memos')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->index(['credit_memo_id', 'invoice_id']);
        });

        Schema::create('credit_memo_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_memo_id')->constrained('credit_memos')->cascadeOnDelete();
            $table->date('refund_date');
            $table->decimal('amount', 15, 2);
            $table->string('method', 50)->default('check');
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->string('reference')->nullable();
            $table->text('memo')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_memo_refunds');
        Schema::dropIfExists('credit_memo_allocations');
    }
};
