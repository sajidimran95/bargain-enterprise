<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->timestamp('cleared_at')->nullable()->after('memo');
        });

        Schema::table('checks', function (Blueprint $table) {
            $table->timestamp('cleared_at')->nullable()->after('memo');
        });
    }

    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropColumn('cleared_at');
        });

        Schema::table('checks', function (Blueprint $table) {
            $table->dropColumn('cleared_at');
        });
    }
};
