<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $inventory = app(InventoryService::class);
        $userId = User::query()->where('email', 'owner@bargain.local')->value('id');
        $items = Item::query()->orderBy('id')->get();

        foreach ($items as $index => $item) {
            $hasOpening = InventoryTransaction::query()
                ->where('item_id', $item->id)
                ->where('type', 'opening_stock')
                ->exists();

            if ($hasOpening) {
                continue;
            }

            $qty = match (true) {
                $index % 40 === 0 => 0,
                $index % 35 === 0 => 4,
                $index % 50 === 0 => -12,
                default => fake()->numberBetween(25, 420),
            };

            if ($qty > 0) {
                $inventory->post($item, [
                    'type' => 'opening_stock',
                    'qty_in' => $qty,
                    'unit_cost' => $item->purchase_cost,
                    'occurred_at' => now()->subMonths(8),
                    'created_by' => $userId,
                    'memo' => 'Opening stock',
                ]);
            } elseif ($qty < 0) {
                $inventory->post($item, [
                    'type' => 'opening_stock',
                    'qty_in' => 20,
                    'unit_cost' => $item->purchase_cost,
                    'occurred_at' => now()->subMonths(8),
                    'created_by' => $userId,
                    'memo' => 'Opening stock',
                ]);
                $inventory->post($item->fresh(), [
                    'type' => 'adjustment',
                    'qty_out' => 32,
                    'unit_cost' => $item->purchase_cost,
                    'occurred_at' => now()->subMonths(2),
                    'created_by' => $userId,
                    'allow_negative' => true,
                    'memo' => 'Controlled negative inventory demo',
                ]);
            }
        }
    }
}
