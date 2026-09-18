<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('menu')->nullable()->after('group')->index();
            $table->string('submenu')->nullable()->after('menu')->index();
            $table->unsignedInteger('sort_order')->default(0)->after('submenu');
        });
    }

    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn(['menu', 'submenu', 'sort_order']);
        });
    }
};
