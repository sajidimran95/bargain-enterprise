<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'bill_number',
    'ref_no',
    'vendor_id',
    'goods_receipt_id',
    'bill_date',
    'due_date',
    'status',
    'subtotal',
    'total',
    'amount_paid',
    'balance_due',
    'memo',
])]
class VendorBill extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'bill_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'total' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
        ];
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(VendorBillLine::class);
    }

    public function paymentAllocations(): HasMany
    {
        return $this->hasMany(VendorPaymentAllocation::class);
    }
}
