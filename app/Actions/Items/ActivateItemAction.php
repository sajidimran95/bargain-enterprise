<?php

namespace App\Actions\Items;

use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ActivateItemAction
{
    public function handle(Item $item): Item
    {
        return DB::transaction(function () use ($item) {
            $item->update(['is_active' => true]);

            return $item->refresh();
        });
    }
}
