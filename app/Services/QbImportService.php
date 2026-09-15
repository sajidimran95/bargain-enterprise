<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\ImportBatch;
use App\Models\ImportRow;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Vendor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class QbImportService
{
    /**
     * @return list<string>
     */
    public function supportedEntities(): array
    {
        return ['categories', 'items', 'customers', 'vendors'];
    }

    public function ingestCsv(UploadedFile $file, string $entity, ?int $userId = null): ImportBatch
    {
        if (! in_array($entity, $this->supportedEntities(), true)) {
            throw new RuntimeException('Unsupported import entity.');
        }

        $handle = fopen($file->getRealPath(), 'rb');
        if ($handle === false) {
            throw new RuntimeException('Unable to read uploaded file.');
        }

        $headers = fgetcsv($handle);
        if ($headers === false || $headers === [null] || $headers === []) {
            fclose($handle);
            throw new RuntimeException('CSV has no header row.');
        }

        $headers = array_map(fn ($h) => Str::of((string) $h)->trim()->lower()->replace(' ', '_')->toString(), $headers);

        return DB::transaction(function () use ($handle, $headers, $file, $entity, $userId) {
            $batch = ImportBatch::query()->create([
                'source' => 'csv',
                'entity' => $entity,
                'original_filename' => $file->getClientOriginalName(),
                'status' => 'raw',
                'created_by' => $userId ?? auth()->id(),
            ]);

            $line = 1;
            $count = 0;
            while (($data = fgetcsv($handle)) !== false) {
                $line++;
                if ($this->rowIsEmpty($data)) {
                    continue;
                }

                $payload = [];
                foreach ($headers as $index => $header) {
                    if ($header === '') {
                        continue;
                    }
                    $payload[$header] = isset($data[$index]) ? trim((string) $data[$index]) : null;
                }

                ImportRow::query()->create([
                    'import_batch_id' => $batch->id,
                    'line_number' => $line,
                    'stage' => 'raw',
                    'raw_payload' => $payload,
                ]);
                $count++;
            }

            fclose($handle);

            $batch->update(['row_count' => $count]);

            return $batch->fresh('rows');
        });
    }

    public function normalize(ImportBatch $batch): ImportBatch
    {
        $batch->load('rows');

        foreach ($batch->rows as $row) {
            $normalized = match ($batch->entity) {
                'categories' => $this->normalizeCategory($row->raw_payload ?? []),
                'items' => $this->normalizeItem($row->raw_payload ?? []),
                'customers' => $this->normalizeCustomer($row->raw_payload ?? []),
                'vendors' => $this->normalizeVendor($row->raw_payload ?? []),
                default => $row->raw_payload ?? [],
            };

            $row->update([
                'normalized_payload' => $normalized,
                'stage' => 'normalized',
                'validation_errors' => null,
            ]);
        }

        $batch->update([
            'status' => 'normalized',
            'normalized_at' => now(),
        ]);

        return $batch->fresh('rows');
    }

    public function validate(ImportBatch $batch): ImportBatch
    {
        $batch->load('rows');
        $errorCount = 0;

        foreach ($batch->rows as $row) {
            $errors = match ($batch->entity) {
                'categories' => $this->validateCategory($row->normalized_payload ?? []),
                'items' => $this->validateItem($row->normalized_payload ?? []),
                'customers' => $this->validateCustomer($row->normalized_payload ?? []),
                'vendors' => $this->validateVendor($row->normalized_payload ?? []),
                default => ['entity' => 'Unknown entity'],
            };

            if ($errors !== []) {
                $errorCount++;
                $row->update([
                    'stage' => 'failed',
                    'validation_errors' => $errors,
                ]);
            } else {
                $row->update([
                    'stage' => 'validated',
                    'validation_errors' => null,
                ]);
            }
        }

        $batch->update([
            'status' => 'validated',
            'error_count' => $errorCount,
            'validated_at' => now(),
        ]);

        return $batch->fresh('rows');
    }

    public function transform(ImportBatch $batch): ImportBatch
    {
        $batch->load('rows');

        foreach ($batch->rows as $row) {
            if ($row->stage === 'failed') {
                continue;
            }

            $transformed = match ($batch->entity) {
                'categories' => $this->transformCategory($row->normalized_payload ?? []),
                'items' => $this->transformItem($row->normalized_payload ?? []),
                'customers' => $this->transformCustomer($row->normalized_payload ?? []),
                'vendors' => $this->transformVendor($row->normalized_payload ?? []),
                default => [],
            };

            $row->update([
                'transformed_payload' => $transformed,
                'stage' => 'transformed',
            ]);
        }

        $batch->update([
            'status' => 'transformed',
            'transformed_at' => now(),
        ]);

        return $batch->fresh('rows');
    }

    public function produce(ImportBatch $batch): ImportBatch
    {
        $batch->load('rows');
        $success = 0;

        DB::transaction(function () use ($batch, &$success) {
            foreach ($batch->rows as $row) {
                if ($row->stage === 'failed' || blank($row->transformed_payload)) {
                    continue;
                }

                $model = match ($batch->entity) {
                    'categories' => $this->produceCategory($row->transformed_payload),
                    'items' => $this->produceItem($row->transformed_payload),
                    'customers' => $this->produceCustomer($row->transformed_payload),
                    'vendors' => $this->produceVendor($row->transformed_payload),
                    default => null,
                };

                if ($model === null) {
                    $row->update([
                        'stage' => 'failed',
                        'validation_errors' => array_merge($row->validation_errors ?? [], ['produce' => 'Could not write production record']),
                    ]);

                    continue;
                }

                $row->update([
                    'stage' => 'production',
                    'production_type' => $model::class,
                    'production_id' => $model->id,
                ]);
                $success++;
            }
        });

        $batch->update([
            'status' => 'production',
            'success_count' => $success,
            'error_count' => max(0, (int) $batch->row_count - $success),
            'produced_at' => now(),
            'summary' => [
                'produced' => $success,
                'failed' => max(0, (int) $batch->row_count - $success),
            ],
        ]);

        return $batch->fresh('rows');
    }

    public function runPipeline(ImportBatch $batch): ImportBatch
    {
        $batch = $this->normalize($batch);
        $batch = $this->validate($batch);
        $batch = $this->transform($batch);

        return $this->produce($batch);
    }

    /**
     * @param  array<int, mixed>  $data
     */
    protected function rowIsEmpty(array $data): bool
    {
        foreach ($data as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function normalizeCategory(array $raw): array
    {
        $name = (string) ($raw['name'] ?? $raw['category'] ?? $raw['category_name'] ?? '');
        $code = (string) ($raw['code'] ?? $raw['category_code'] ?? Str::upper(Str::slug($name, '_')));

        return [
            'code' => Str::limit(trim($code), 50, ''),
            'name' => trim($name),
            'description' => trim((string) ($raw['description'] ?? '')) ?: null,
            'is_active' => $this->toBool($raw['is_active'] ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function normalizeItem(array $raw): array
    {
        $sku = trim((string) ($raw['sku'] ?? $raw['item'] ?? $raw['name'] ?? ''));
        $name = trim((string) ($raw['name'] ?? $raw['description'] ?? $sku));
        $category = trim((string) ($raw['category'] ?? $raw['category_name'] ?? $raw['category_code'] ?? ''));
        $type = Str::of((string) ($raw['type'] ?? $raw['item_type'] ?? 'inventory_part'))
            ->lower()
            ->replace(' ', '_')
            ->toString();

        if (in_array($type, ['inv', 'inventory', 'inventorypart'], true)) {
            $type = 'inventory_part';
        }

        return [
            'sku' => $sku,
            'barcode' => trim((string) ($raw['barcode'] ?? $raw['upc'] ?? '')) ?: null,
            'name' => $name,
            'type' => $type !== '' ? $type : 'inventory_part',
            'sales_price' => $this->toDecimal($raw['sales_price'] ?? $raw['price'] ?? 0),
            'purchase_cost' => $this->toDecimal($raw['purchase_cost'] ?? $raw['cost'] ?? 0),
            'on_hand' => $this->toDecimal($raw['on_hand'] ?? $raw['qty'] ?? $raw['quantity'] ?? 0, 4),
            'category' => $category !== '' ? $category : null,
            'promotion' => trim((string) ($raw['promotion'] ?? '')) ?: null,
            'is_active' => $this->toBool($raw['is_active'] ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function normalizeCustomer(array $raw): array
    {
        $name = trim((string) ($raw['display_name'] ?? $raw['name'] ?? $raw['company'] ?? $raw['company_name'] ?? ''));

        return [
            'display_name' => $name,
            'company_name' => trim((string) ($raw['company_name'] ?? $raw['company'] ?? $name)) ?: null,
            'email' => trim((string) ($raw['email'] ?? '')) ?: null,
            'phone' => trim((string) ($raw['phone'] ?? '')) ?: null,
            'bill_to_street1' => trim((string) ($raw['bill_to_street1'] ?? $raw['billing_address_line1'] ?? $raw['address'] ?? $raw['street'] ?? '')) ?: null,
            'bill_to_street2' => trim((string) ($raw['bill_to_street2'] ?? $raw['billing_address_line2'] ?? $raw['street2'] ?? '')) ?: null,
            'bill_to_city' => trim((string) ($raw['bill_to_city'] ?? $raw['billing_city'] ?? $raw['city'] ?? '')) ?: null,
            'bill_to_state' => trim((string) ($raw['bill_to_state'] ?? $raw['billing_state'] ?? $raw['state'] ?? '')) ?: null,
            'bill_to_zip' => trim((string) ($raw['bill_to_zip'] ?? $raw['billing_postal'] ?? $raw['zip'] ?? $raw['postal'] ?? '')) ?: null,
            'is_active' => $this->toBool($raw['is_active'] ?? true),
            'balance' => $this->toDecimal($raw['balance'] ?? 0),
        ];
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    protected function normalizeVendor(array $raw): array
    {
        $name = trim((string) ($raw['display_name'] ?? $raw['name'] ?? $raw['company'] ?? $raw['company_name'] ?? ''));

        return [
            'display_name' => $name,
            'company_name' => trim((string) ($raw['company_name'] ?? $raw['company'] ?? $name)) ?: null,
            'email' => trim((string) ($raw['email'] ?? '')) ?: null,
            'phone' => trim((string) ($raw['phone'] ?? '')) ?: null,
            'is_active' => $this->toBool($raw['is_active'] ?? true),
            'balance' => $this->toDecimal($raw['balance'] ?? 0),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    protected function validateCategory(array $data): array
    {
        $errors = [];
        if (blank($data['name'] ?? null)) {
            $errors['name'] = 'Category name is required.';
        }
        if (blank($data['code'] ?? null)) {
            $errors['code'] = 'Category code is required.';
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    protected function validateItem(array $data): array
    {
        $errors = [];
        if (blank($data['sku'] ?? null)) {
            $errors['sku'] = 'SKU / Item is required.';
        }
        if (blank($data['name'] ?? null)) {
            $errors['name'] = 'Item name is required.';
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    protected function validateCustomer(array $data): array
    {
        $errors = [];
        if (blank($data['display_name'] ?? null)) {
            $errors['display_name'] = 'Customer name is required.';
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    protected function validateVendor(array $data): array
    {
        $errors = [];
        if (blank($data['display_name'] ?? null)) {
            $errors['display_name'] = 'Vendor name is required.';
        }

        return $errors;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function transformCategory(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function transformItem(array $data): array
    {
        $categoryId = null;
        if (filled($data['category'] ?? null)) {
            $category = ItemCategory::query()
                ->where('code', $data['category'])
                ->orWhere('name', $data['category'])
                ->first();

            if (! $category) {
                $category = ItemCategory::query()->create([
                    'code' => Str::upper(Str::slug((string) $data['category'], '_')),
                    'name' => (string) $data['category'],
                    'is_active' => true,
                ]);
            }
            $categoryId = $category->id;
        }

        return [
            'sku' => $data['sku'],
            'barcode' => $data['barcode'] ?? null,
            'name' => $data['name'],
            'type' => $data['type'] ?? 'inventory_part',
            'sales_description' => $data['name'],
            'purchase_description' => $data['name'],
            'sales_price' => $data['sales_price'] ?? 0,
            'purchase_cost' => $data['purchase_cost'] ?? 0,
            'on_hand' => $data['on_hand'] ?? 0,
            'item_category_id' => $categoryId,
            'promotion' => $data['promotion'] ?? null,
            'is_active' => (bool) ($data['is_active'] ?? true),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function transformCustomer(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function transformVendor(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function produceCategory(array $data): ItemCategory
    {
        return ItemCategory::query()->updateOrCreate(
            ['code' => $data['code']],
            [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? true),
            ]
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function produceItem(array $data): Item
    {
        return Item::query()->updateOrCreate(
            ['sku' => $data['sku']],
            $data
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function produceCustomer(array $data): Customer
    {
        return Customer::query()->updateOrCreate(
            ['display_name' => $data['display_name']],
            $data
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function produceVendor(array $data): Vendor
    {
        return Vendor::query()->updateOrCreate(
            ['display_name' => $data['display_name']],
            $data
        );
    }

    protected function toBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        $normalized = Str::lower(trim((string) $value));

        return ! in_array($normalized, ['0', 'false', 'no', 'n', 'inactive'], true);
    }

    protected function toDecimal(mixed $value, int $scale = 2): string
    {
        $number = is_numeric($value) ? (float) $value : 0.0;

        return number_format($number, $scale, '.', '');
    }
}
