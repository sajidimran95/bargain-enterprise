<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'credit_number',
    'customer_id',
    'credit_date',
    'status',
    'subtotal',
    'tax_total',
    'total',
    'remaining_credit',
    'memo',
    'class',
    'template',
    'po_number',
    'print_later',
    'email_later',
    'is_pending',
])]
class CreditMemo extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'credit_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
            'remaining_credit' => 'decimal:2',
            'print_later' => 'boolean',
            'email_later' => 'boolean',
            'is_pending' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(CreditMemoLine::class);
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(CreditMemoAllocation::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(CreditMemoRefund::class);
    }

    public function hasRemainingCredit(): bool
    {
        return bccomp((string) $this->remaining_credit, '0', 2) > 0;
    }
}
