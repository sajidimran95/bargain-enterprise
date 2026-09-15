<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('barcode', 64)->nullable()->after('sku')->index();
        });

        // Existing SKUs double as scan codes until a dedicated barcode is set.
        DB::table('items')->whereNull('barcode')->update([
            'barcode' => DB::raw('sku'),
        ]);
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('barcode');
        });
    }
};
