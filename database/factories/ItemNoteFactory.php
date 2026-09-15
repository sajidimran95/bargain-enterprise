<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemNote>
 */
class ItemNoteFactory extends Factory
{
    protected $model = ItemNote::class;

    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'user_id' => null,
            'body' => fake()->sentence(),
        ];
    }
}
