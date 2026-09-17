<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'item_id',
    'event',
    'qty_in',
    'qty_out',
    'balance_after',
    'old_value',
    'new_value',
    'unit_cost',
    'suggested_sales_price',
    'reference_type',
    'reference_id',
    'created_by',
    'memo',
    'occurred_at',
])]
class ItemHistory extends Model
{
    use HasFactory;

    public const EVENT_STOCK_IN = 'stock_in';

    public const EVENT_STOCK_OUT = 'stock_out';

    public const EVENT_PURCHASE_COST = 'purchase_cost_change';

    public const EVENT_SALES_PRICE = 'sales_price_change';

    public const EVENT_PO_ORDERED = 'po_ordered';

    protected function casts(): array
    {
        return [
            'qty_in' => 'decimal:4',
            'qty_out' => 'decimal:4',
            'balance_after' => 'decimal:4',
            'old_value' => 'decimal:4',
            'new_value' => 'decimal:4',
            'unit_cost' => 'decimal:4',
            'suggested_sales_price' => 'decimal:2',
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

    public function label(): string
    {
        return match ($this->event) {
            self::EVENT_STOCK_IN => 'Stock increase',
            self::EVENT_STOCK_OUT => 'Stock decrease',
            self::EVENT_PURCHASE_COST => 'PO / purchase cost change',
            self::EVENT_SALES_PRICE => 'Sales price change',
            self::EVENT_PO_ORDERED => 'Ordered on PO',
            default => str_replace('_', ' ', $this->event),
        };
    }
}
