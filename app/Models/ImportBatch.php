<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'uuid',
    'source',
    'entity',
    'original_filename',
    'status',
    'row_count',
    'error_count',
    'success_count',
    'summary',
    'created_by',
    'normalized_at',
    'validated_at',
    'transformed_at',
    'produced_at',
])]
class ImportBatch extends Model
{
    protected static function booted(): void
    {
        static::creating(function (ImportBatch $batch): void {
            if (blank($batch->uuid)) {
                $batch->uuid = (string) Str::uuid();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'summary' => 'array',
            'normalized_at' => 'datetime',
            'validated_at' => 'datetime',
            'transformed_at' => 'datetime',
            'produced_at' => 'datetime',
        ];
    }

    public function rows(): HasMany
    {
        return $this->hasMany(ImportRow::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
