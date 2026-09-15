<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_batches', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('source')->default('csv');
            $table->string('entity');
            $table->string('original_filename')->nullable();
            $table->string('status')->default('raw');
            $table->unsignedInteger('row_count')->default(0);
            $table->unsignedInteger('error_count')->default(0);
            $table->unsignedInteger('success_count')->default(0);
            $table->json('summary')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('normalized_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('transformed_at')->nullable();
            $table->timestamp('produced_at')->nullable();
            $table->timestamps();

            $table->index(['entity', 'status']);
        });

        Schema::create('import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('import_batch_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('line_number');
            $table->string('stage')->default('raw');
            $table->json('raw_payload');
            $table->json('normalized_payload')->nullable();
            $table->json('validation_errors')->nullable();
            $table->json('transformed_payload')->nullable();
            $table->string('production_type')->nullable();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->timestamps();

            $table->index(['import_batch_id', 'stage']);
            $table->index(['production_type', 'production_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_rows');
        Schema::dropIfExists('import_batches');
    }
};
