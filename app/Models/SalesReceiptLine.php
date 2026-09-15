<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'sales_receipt_id',
    'item_id',
    'description',
    'quantity',
    'rate',
    'amount',
    'taxable',
])]
class SalesReceiptLine extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'rate' => 'decimal:2',
            'amount' => 'decimal:2',
            'taxable' => 'boolean',
        ];
    }

    public function salesReceipt(): BelongsTo
    {
        return $this->belongsTo(SalesReceipt::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
