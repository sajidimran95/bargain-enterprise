<?php

namespace App\Livewire\Concerns;

use App\Models\Item;
use App\Support\ItemCatalog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait WithLineItems
{
    /** @var array<int, array<string, mixed>> */
    public array $lines = [];

    public string $scanCode = '';

    /** @var list<array{id: int, code: string, label: string}> */
    public array $itemSearchResults = [];

    public function addLine(): void
    {
        $this->lines[] = $this->emptyLine();
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);

        if ($this->lines === []) {
            $this->addLine();
        }
    }

    public function updatedScanCode(string $value): void
    {
        $code = trim($value);
        if ($code === '') {
            $this->itemSearchResults = [];

            return;
        }

        // Exact code match (scanner or full typed code) → auto-add.
        $item = $this->findItemByScanCode($code, exact: true);
        if ($item) {
            $this->applyScannedItem($item);
            $this->scanCode = '';
            $this->itemSearchResults = [];
            $this->dispatch('be-toast', message: 'Added '.$this->itemDisplayCode($item));

            return;
        }

        $this->itemSearchResults = ItemCatalog::search(
            $code,
            25,
            $this->itemSearchUsesPurchaseCatalog()
        );
    }

    public function selectItemSearchResult(int $itemId): void
    {
        $item = Item::query()->active()->find($itemId);
        if (! $item) {
            $this->dispatch('be-toast', message: 'Item not found.');

            return;
        }

        $this->applyScannedItem($item);
        $this->scanCode = '';
        $this->itemSearchResults = [];
        $this->dispatch('be-toast', message: 'Added '.$this->itemDisplayCode($item));
    }

    public function clearItemSearch(): void
    {
        $this->itemSearchResults = [];
    }

    public function scanItem(): void
    {
        $code = trim($this->scanCode);
        if ($code === '') {
            return;
        }

        $item = $this->findItemByScanCode($code);
        if (! $item) {
            $this->dispatch('be-toast', message: 'No item found for code: '.$code);
            $this->scanCode = '';
            $this->itemSearchResults = [];

            return;
        }

        $this->applyScannedItem($item);
        $this->scanCode = '';
        $this->itemSearchResults = [];
        $this->dispatch('be-toast', message: 'Scanned '.$this->itemDisplayCode($item));
    }

    public function updatedLines(mixed $value, ?string $key = null): void
    {
        if ($key === null || ! str_contains($key, '.')) {
            return;
        }

        [$index, $field] = explode('.', $key, 2);
        $index = (int) $index;

        if (! isset($this->lines[$index])) {
            return;
        }

        if ($field === 'item_code' && filled($value)) {
            $item = $this->findItemByScanCode((string) $value);
            if ($item) {
                $this->fillLineFromItem($index, $item);

                return;
            }

            $this->dispatch('be-toast', message: 'Item code not found: '.$value);
            $this->lines[$index]['item_id'] = '';
            $this->lines[$index]['description'] = '';
            $this->lines[$index]['rate'] = '0.00';
            $this->lines[$index]['amount'] = '0.00';

            return;
        }

        if ($field === 'item_id' && $value) {
            $item = Item::query()->find($value);
            if ($item) {
                $this->fillLineFromItem($index, $item);

                return;
            }
        }

        $qty = (float) ($this->lines[$index]['quantity'] ?? 0);
        $rate = (float) ($this->lines[$index]['rate'] ?? 0);
        $this->lines[$index]['amount'] = number_format($qty * $rate, 2, '.', '');
    }

    protected function applyScannedItem(Item $item): void
    {
        foreach ($this->lines as $index => $line) {
            if ((int) ($line['item_id'] ?? 0) === $item->id) {
                $qty = (float) ($line['quantity'] ?? 0) + 1;
                $this->lines[$index]['quantity'] = number_format($qty, 2, '.', '');
                $rate = (float) ($this->lines[$index]['rate'] ?? 0);
                $this->lines[$index]['amount'] = number_format($qty * $rate, 2, '.', '');

                return;
            }
        }

        foreach ($this->lines as $index => $line) {
            if (blank($line['item_id'] ?? null) && blank($line['item_code'] ?? null)) {
                $this->fillLineFromItem($index, $item);

                return;
            }
        }

        $this->addLine();
        $this->fillLineFromItem(count($this->lines) - 1, $item);
    }

    protected function fillLineFromItem(int $index, Item $item): void
    {
        $rate = number_format((float) $this->lineRateForItem($item), 2, '.', '');
        $qty = (float) ($this->lines[$index]['quantity'] ?? 1);
        if ($qty <= 0) {
            $qty = 1;
        }

        $preserved = $this->lines[$index] ?? [];

        $this->lines[$index] = array_merge($preserved, [
            'item_id' => (string) $item->id,
            'item_code' => $this->itemDisplayCode($item),
            'description' => $this->lineDescriptionForItem($item),
            'quantity' => number_format($qty, 2, '.', ''),
            'rate' => $rate,
            'amount' => number_format($qty * (float) $rate, 2, '.', ''),
            'taxable' => $preserved['taxable'] ?? true,
            'class' => $preserved['class'] ?? '',
        ]);
    }

    protected function findItemByScanCode(string $code, bool $exact = false): ?Item
    {
        $code = trim($code);
        if ($code === '') {
            return null;
        }

        $exactMatch = Item::query()
            ->active()
            ->where(function ($q) use ($code) {
                $q->where('barcode', $code)
                    ->orWhere('sku', $code)
                    ->orWhere('manufacturer_part_number', $code);
            })
            ->orderByRaw("LOWER(COALESCE(NULLIF(barcode, ''), sku)) asc")
            ->first();

        if ($exactMatch || $exact) {
            return $exactMatch;
        }

        // Case-insensitive exact, then unique prefix match for typed codes.
        $ciExact = Item::query()
            ->active()
            ->where(function ($q) use ($code) {
                $q->whereRaw('LOWER(barcode) = ?', [mb_strtolower($code)])
                    ->orWhereRaw('LOWER(sku) = ?', [mb_strtolower($code)])
                    ->orWhereRaw('LOWER(manufacturer_part_number) = ?', [mb_strtolower($code)]);
            })
            ->orderByRaw("LOWER(COALESCE(NULLIF(barcode, ''), sku)) asc")
            ->first();

        if ($ciExact) {
            return $ciExact;
        }

        $prefix = Item::query()
            ->active()
            ->where(function ($q) use ($code) {
                $like = mb_strtolower($code).'%';
                $q->whereRaw('LOWER(barcode) like ?', [$like])
                    ->orWhereRaw('LOWER(sku) like ?', [$like]);
            })
            ->orderByRaw("LOWER(COALESCE(NULLIF(barcode, ''), sku)) asc")
            ->limit(2)
            ->get();

        return $prefix->count() === 1 ? $prefix->first() : null;
    }

    protected function itemDisplayCode(Item $item): string
    {
        return (string) ($item->barcode ?: $item->sku);
    }

    protected function itemSearchUsesPurchaseCatalog(): bool
    {
        return false;
    }

    protected function lineDescriptionForItem(Item $item): string
    {
        return (string) ($item->sales_description ?: $item->name);
    }

    /**
     * @return array<string, mixed>
     */
    protected function emptyLine(): array
    {
        return [
            'item_id' => '',
            'item_code' => '',
            'description' => '',
            'quantity' => '1.00',
            'rate' => '0.00',
            'amount' => '0.00',
            'taxable' => true,
            'class' => '',
        ];
    }

    protected function lineRateForItem(Item $item): float|string
    {
        return $item->sales_price;
    }

    protected function linesSubtotal(): string
    {
        $sum = '0.00';
        foreach ($this->lines as $line) {
            $sum = bcadd($sum, number_format((float) ($line['amount'] ?? 0), 2, '.', ''), 2);
        }

        return $sum;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function validatedLinePayload(): array
    {
        $filled = array_values(array_filter(
            $this->lines,
            fn (array $line) => filled($line['item_id'] ?? null)
        ));

        if ($filled === []) {
            throw ValidationException::withMessages([
                'lines' => 'At least one line item is required.',
            ]);
        }

        $validator = Validator::make(
            ['lines' => $filled],
            [
                'lines' => ['required', 'array', 'min:1'],
                'lines.*.item_id' => ['required', 'exists:items,id'],
                'lines.*.description' => ['nullable', 'string', 'max:255'],
                'lines.*.quantity' => ['required', 'numeric', 'gt:0'],
                'lines.*.rate' => ['required', 'numeric', 'min:0'],
                'lines.*.taxable' => ['boolean'],
            ]
        );

        return array_values($validator->validate()['lines']);
    }
}
