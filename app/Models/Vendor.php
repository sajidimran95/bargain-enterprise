<?php

namespace App\Models;

use Database\Factories\VendorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'vendor_number',
    'company_name',
    'display_name',
    'first_name',
    'last_name',
    'email',
    'phone',
    'fax',
    'bill_from_street1',
    'bill_from_street2',
    'bill_from_city',
    'bill_from_state',
    'bill_from_zip',
    'bill_from_country',
    'terms',
    'account_number',
    'balance',
    'notes',
    'is_active',
])]
class Vendor extends Model
{
    /** @use HasFactory<VendorFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(VendorContact::class);
    }

    public function notesRelation(): HasMany
    {
        return $this->hasMany(VendorNote::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'preferred_vendor_id');
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function vendorBills(): HasMany
    {
        return $this->hasMany(VendorBill::class);
    }

    public function fullName(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? '')) ?: $this->display_name;
    }

    /**
     * @return list<string>
     */
    public function billFromLines(): array
    {
        $lines = array_filter([
            $this->company_name ?: $this->display_name,
            $this->bill_from_street1,
            $this->bill_from_street2,
            trim(implode(', ', array_filter([$this->bill_from_city, $this->bill_from_state, $this->bill_from_zip]))),
        ]);

        return array_values($lines);
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
                ->orWhere('vendor_number', 'like', $like)
                ->orWhere('email', 'like', $like)
                ->orWhere('phone', 'like', $like);
        });
    }
}
