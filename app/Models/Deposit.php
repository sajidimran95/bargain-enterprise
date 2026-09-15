<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'number',
    'bank_account_id',
    'deposit_date',
    'total',
    'memo',
    'cleared_at',
])]
class Deposit extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'deposit_date' => 'date',
            'total' => 'decimal:2',
            'cleared_at' => 'datetime',
        ];
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(DepositItem::class);
    }
}
