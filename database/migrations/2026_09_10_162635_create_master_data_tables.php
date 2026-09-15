<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->decimal('adjustment_percent', 8, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tax_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->decimal('rate', 8, 4)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('units_of_measure', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('abbreviation', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('item_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_number')->nullable()->unique();
            $table->string('company_name');
            $table->string('display_name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('alt_phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('bill_to_street1')->nullable();
            $table->string('bill_to_street2')->nullable();
            $table->string('bill_to_city')->nullable();
            $table->string('bill_to_state', 50)->nullable();
            $table->string('bill_to_zip', 20)->nullable();
            $table->string('bill_to_country')->nullable();
            $table->foreignId('price_level_id')->nullable()->constrained('price_levels')->nullOnDelete();
            $table->foreignId('tax_code_id')->nullable()->constrained('tax_codes')->nullOnDelete();
            $table->string('terms')->nullable();
            $table->decimal('credit_limit', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->boolean('online_payment_eligible')->default(false);
            $table->text('pinned_note')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_name');
            $table->index('display_name');
        });

        Schema::create('customer_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('customer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
        });

        Schema::create('customer_todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('notes')->nullable();
            $table->date('due_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_number')->nullable()->unique();
            $table->string('company_name');
            $table->string('display_name');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable()->index();
            $table->string('fax')->nullable();
            $table->string('bill_from_street1')->nullable();
            $table->string('bill_from_street2')->nullable();
            $table->string('bill_from_city')->nullable();
            $table->string('bill_from_state', 50)->nullable();
            $table->string('bill_from_zip', 20)->nullable();
            $table->string('bill_from_country')->nullable();
            $table->string('terms')->nullable();
            $table->string('account_number')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('company_name');
            $table->index('display_name');
        });

        Schema::create('vendor_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('vendor_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('type')->default('inventory_part');
            $table->foreignId('parent_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('manufacturer_part_number')->nullable();
            $table->foreignId('unit_of_measure_id')->nullable()->constrained('units_of_measure')->nullOnDelete();
            $table->text('purchase_description')->nullable();
            $table->decimal('purchase_cost', 15, 2)->default(0);
            $table->string('cogs_account')->nullable();
            $table->foreignId('preferred_vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->text('sales_description')->nullable();
            $table->decimal('sales_price', 15, 2)->default(0);
            $table->foreignId('tax_code_id')->nullable()->constrained('tax_codes')->nullOnDelete();
            $table->string('income_account')->nullable();
            $table->string('asset_account')->nullable();
            $table->decimal('reorder_min', 15, 4)->nullable();
            $table->decimal('reorder_max', 15, 4)->nullable();
            $table->decimal('on_hand', 15, 4)->default(0);
            $table->decimal('average_cost', 15, 4)->default(0);
            $table->decimal('on_po_qty', 15, 4)->default(0);
            $table->decimal('on_so_qty', 15, 4)->default(0);
            $table->foreignId('item_category_id')->nullable()->constrained('item_categories')->nullOnDelete();
            $table->foreignId('item_type_id')->nullable()->constrained('item_types')->nullOnDelete();
            $table->decimal('items_per_container', 15, 4)->nullable();
            $table->string('promotion')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('item_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('vendor_notes');
        Schema::dropIfExists('vendor_contacts');
        Schema::dropIfExists('vendors');
        Schema::dropIfExists('customer_todos');
        Schema::dropIfExists('customer_notes');
        Schema::dropIfExists('customer_contacts');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('item_types');
        Schema::dropIfExists('item_categories');
        Schema::dropIfExists('units_of_measure');
        Schema::dropIfExists('tax_codes');
        Schema::dropIfExists('price_levels');
    }
};
