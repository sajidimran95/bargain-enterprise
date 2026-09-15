<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'number',
    'customer_id',
    'quote_date',
    'expiry_date',
    'status',
    'subtotal',
    'tax_total',
    'total',
    'memo',
    'converted_invoice_id',
])]
class Quote extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'quote_date' => 'date',
            'expiry_date' => 'date',
            'subtotal' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function convertedInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'converted_invoice_id');
    }

    public function lines(): HasMany
    {
        return $this->hasMany(QuoteLine::class);
    }
}
