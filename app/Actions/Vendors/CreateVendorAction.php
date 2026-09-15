<?php

namespace App\Actions\Vendors;

use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateVendorAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Vendor
    {
        $validated = $this->validate($data);

        return DB::transaction(function () use ($validated) {
            if (empty($validated['display_name'])) {
                $validated['display_name'] = $validated['company_name'];
            }

            return Vendor::query()->create($validated);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'vendor_number' => ['nullable', 'string', 'max:50', 'unique:vendors,vendor_number'],
            'company_name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:50'],
            'bill_from_street1' => ['nullable', 'string', 'max:255'],
            'bill_from_street2' => ['nullable', 'string', 'max:255'],
            'bill_from_city' => ['nullable', 'string', 'max:100'],
            'bill_from_state' => ['nullable', 'string', 'max:50'],
            'bill_from_zip' => ['nullable', 'string', 'max:20'],
            'bill_from_country' => ['nullable', 'string', 'max:100'],
            'terms' => ['nullable', 'string', 'max:100'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
