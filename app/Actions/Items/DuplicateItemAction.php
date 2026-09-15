<?php

namespace App\Actions\Items;

use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DuplicateItemAction
{
    public function handle(Item $item): Item
    {
        return DB::transaction(function () use ($item) {
            $copy = $item->replicate(['on_hand', 'average_cost', 'on_po_qty', 'on_so_qty']);
            $copy->sku = $this->uniqueSku($item->sku);
            $copy->name = $item->name.' (Copy)';
            $copy->sales_description = ($item->sales_description ?: $item->name).' (Copy)';
            $copy->on_hand = 0;
            $copy->average_cost = 0;
            $copy->on_po_qty = 0;
            $copy->on_so_qty = 0;
            $copy->is_active = true;
            $copy->save();

            return $copy->refresh();
        });
    }

    protected function uniqueSku(string $base): string
    {
        $candidate = Str::limit($base.'-COPY', 100, '');
        $i = 1;

        while (Item::query()->where('sku', $candidate)->exists()) {
            $candidate = Str::limit($base.'-COPY'.$i, 100, '');
            $i++;
        }

        return $candidate;
    }
}
