<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'check_number',
    'bank_account_id',
    'vendor_id',
    'check_date',
    'amount',
    'payee',
    'memo',
    'cleared_at',
])]
class Check extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'check_date' => 'date',
            'amount' => 'decimal:2',
            'cleared_at' => 'datetime',
        ];
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
