<?php

namespace App\Models;

use Database\Factories\CustomerNoteFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'customer_id',
    'user_id',
    'body',
    'is_pinned',
])]
class CustomerNote extends Model
{
    /** @use HasFactory<CustomerNoteFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
