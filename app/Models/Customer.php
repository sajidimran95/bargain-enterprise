<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'customer_number',
    'company_name',
    'display_name',
    'first_name',
    'last_name',
    'email',
    'phone',
    'alt_phone',
    'fax',
    'bill_to_street1',
    'bill_to_street2',
    'bill_to_city',
    'bill_to_state',
    'bill_to_zip',
    'bill_to_country',
    'price_level_id',
    'tax_code_id',
    'terms',
    'credit_limit',
    'balance',
    'online_payment_eligible',
    'pinned_note',
    'is_active',
])]
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'credit_limit' => 'decimal:2',
            'balance' => 'decimal:2',
            'online_payment_eligible' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function priceLevel(): BelongsTo
    {
        return $this->belongsTo(PriceLevel::class);
    }

    public function taxCode(): BelongsTo
    {
        return $this->belongsTo(TaxCode::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(CustomerContact::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CustomerNote::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(CustomerTodo::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function creditMemos(): HasMany
    {
        return $this->hasMany(CreditMemo::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function fullName(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? '')) ?: $this->display_name;
    }

    public function billToLines(): array
    {
        return array_values(array_filter([
            $this->company_name ?: $this->display_name,
            $this->bill_to_street1,
            $this->bill_to_street2,
            trim(implode(', ', array_filter([
                $this->bill_to_city,
                $this->bill_to_state,
                $this->bill_to_zip,
            ]))),
            $this->bill_to_country,
        ]));
    }

    public function formattedBillingAddress(): string
    {
        return implode("\n", $this->billToLines());
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('display_name', 'like', $like)
                ->orWhere('company_name', 'like', $like)
                ->orWhere('customer_number', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('phone', 'like', $like)
                ->orWhere('first_name', 'like', $like)
                ->orWhere('last_name', 'like', $like);
        });
    }
}
