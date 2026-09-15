<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'payment_number',
    'customer_id',
    'payment_date',
    'amount',
    'unapplied_amount',
    'method',
    'reference',
    'deposit_to_account_id',
    'deposited',
    'memo',
    'created_by',
])]
class Payment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
            'unapplied_amount' => 'decimal:2',
            'deposited' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function depositToAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'deposit_to_account_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class);
    }
}
