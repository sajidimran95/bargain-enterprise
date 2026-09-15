<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'item_id',
    'type',
    'reference_type',
    'reference_id',
    'qty_in',
    'qty_out',
    'unit_cost',
    'balance_after',
    'created_by',
    'memo',
    'occurred_at',
])]
class InventoryTransaction extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'qty_in' => 'decimal:4',
            'qty_out' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'balance_after' => 'decimal:4',
            'occurred_at' => 'datetime',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
