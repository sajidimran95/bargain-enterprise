<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateCustomerAction
{
    public function __construct(protected AuditLogger $audit) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Customer $customer, array $data): Customer
    {
        $validated = $this->validate($customer, $data);

        return DB::transaction(function () use ($customer, $validated) {
            if (empty($validated['display_name'])) {
                $validated['display_name'] = $validated['company_name'];
            }

            $old = $this->audit->snapshot($customer);
            $customer->update($validated);
            $customer->refresh();
            [$changedOld, $changedNew] = $this->changedPairs($old, $this->audit->snapshot($customer));

            if ($changedNew !== []) {
                $this->audit->record('updated', $customer, $changedOld, $changedNew);
            }

            return $customer;
        });
    }

    /**
     * @param  array<string, mixed>  $old
     * @param  array<string, mixed>  $new
     * @return array{0: array<string, mixed>, 1: array<string, mixed>}
     */
    protected function changedPairs(array $old, array $new): array
    {
        $changedOld = [];
        $changedNew = [];

        foreach (array_unique([...array_keys($old), ...array_keys($new)]) as $key) {
            $left = $old[$key] ?? null;
            $right = $new[$key] ?? null;
            if ($left != $right) {
                $changedOld[$key] = $left;
                $changedNew[$key] = $right;
            }
        }

        return [$changedOld, $changedNew];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validate(Customer $customer, array $data): array
    {
        $validator = Validator::make($data, [
            'customer_number' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('customers', 'customer_number')->ignore($customer->id),
            ],
            'company_name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'alt_phone' => ['nullable', 'string', 'max:50'],
            'fax' => ['nullable', 'string', 'max:50'],
            'bill_to_street1' => ['nullable', 'string', 'max:255'],
            'bill_to_street2' => ['nullable', 'string', 'max:255'],
            'bill_to_city' => ['nullable', 'string', 'max:100'],
            'bill_to_state' => ['nullable', 'string', 'max:50'],
            'bill_to_zip' => ['nullable', 'string', 'max:20'],
            'bill_to_country' => ['nullable', 'string', 'max:100'],
            'price_level_id' => ['nullable', 'exists:price_levels,id'],
            'tax_code_id' => ['nullable', 'exists:tax_codes,id'],
            'terms' => ['nullable', 'string', 'max:100'],
            'credit_limit' => ['nullable', 'numeric'],
            'online_payment_eligible' => ['sometimes', 'boolean'],
            'pinned_note' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
