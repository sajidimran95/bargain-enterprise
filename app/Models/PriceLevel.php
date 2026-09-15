<?php

namespace App\Models;

use Database\Factories\PriceLevelFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'adjustment_percent',
    'is_active',
])]
class PriceLevel extends Model
{
    /** @use HasFactory<PriceLevelFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'adjustment_percent' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
