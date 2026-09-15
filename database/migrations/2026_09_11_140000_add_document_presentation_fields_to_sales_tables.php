<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('class')->nullable()->after('memo');
            $table->string('template')->nullable()->after('class');
            $table->boolean('print_later')->default(false)->after('template');
            $table->boolean('email_later')->default(false)->after('print_later');
            $table->boolean('is_pending')->default(false)->after('email_later');
        });

        Schema::table('credit_memos', function (Blueprint $table) {
            $table->string('class')->nullable()->after('memo');
            $table->string('template')->nullable()->after('class');
            $table->string('po_number')->nullable()->after('template');
            $table->boolean('print_later')->default(false)->after('po_number');
            $table->boolean('email_later')->default(false)->after('print_later');
            $table->boolean('is_pending')->default(false)->after('email_later');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['class', 'template', 'print_later', 'email_later', 'is_pending']);
        });

        Schema::table('credit_memos', function (Blueprint $table) {
            $table->dropColumn(['class', 'template', 'po_number', 'print_later', 'email_later', 'is_pending']);
        });
    }
};
