<?php

namespace App\Livewire\Customers;

use App\Actions\Customers\CreateCustomerAction;
use App\Actions\Customers\UpdateCustomerAction;
use App\Models\Customer;
use App\Models\PriceLevel;
use App\Models\TaxCode;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Customer')]
class CustomerForm extends Component
{
    use AuthorizesRequests;

    public ?Customer $customer = null;

    public ?string $customer_number = '';

    public string $company_name = '';

    public string $display_name = '';

    public ?string $first_name = '';

    public ?string $last_name = '';

    public ?string $email = '';

    public ?string $phone = '';

    public ?string $alt_phone = '';

    public ?string $fax = '';

    public ?string $bill_to_street1 = '';

    public ?string $bill_to_street2 = '';

    public ?string $bill_to_city = '';

    public ?string $bill_to_state = '';

    public ?string $bill_to_zip = '';

    public ?string $bill_to_country = '';

    public ?int $price_level_id = null;

    public ?int $tax_code_id = null;

    public ?string $terms = '';

    public ?string $credit_limit = null;

    public bool $online_payment_eligible = false;

    public ?string $pinned_note = '';

    public bool $is_active = true;

    public function mount(?Customer $customer = null): void
    {
        if ($customer?->exists) {
            $this->authorize('update', $customer);
            $this->customer = $customer;
            $data = $customer->only([
                'customer_number', 'company_name', 'display_name', 'first_name', 'last_name',
                'email', 'phone', 'alt_phone', 'fax', 'bill_to_street1', 'bill_to_street2',
                'bill_to_city', 'bill_to_state', 'bill_to_zip', 'bill_to_country',
                'price_level_id', 'tax_code_id', 'terms', 'credit_limit',
                'online_payment_eligible', 'pinned_note', 'is_active',
            ]);

            foreach ([
                'customer_number', 'company_name', 'display_name', 'first_name', 'last_name',
                'email', 'phone', 'alt_phone', 'fax', 'bill_to_street1', 'bill_to_street2',
                'bill_to_city', 'bill_to_state', 'bill_to_zip', 'bill_to_country', 'terms', 'pinned_note',
            ] as $stringField) {
                $data[$stringField] = (string) ($data[$stringField] ?? '');
            }

            $this->fill($data);
            $this->credit_limit = $customer->credit_limit !== null ? (string) $customer->credit_limit : null;
        } else {
            $this->authorize('create', Customer::class);
        }
    }

    public function save(CreateCustomerAction $create, UpdateCustomerAction $update): mixed
    {
        $payload = [
            'customer_number' => $this->customer_number ?: null,
            'company_name' => $this->company_name,
            'display_name' => $this->display_name ?: $this->company_name,
            'first_name' => $this->first_name ?: null,
            'last_name' => $this->last_name ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'alt_phone' => $this->alt_phone ?: null,
            'fax' => $this->fax ?: null,
            'bill_to_street1' => $this->bill_to_street1 ?: null,
            'bill_to_street2' => $this->bill_to_street2 ?: null,
            'bill_to_city' => $this->bill_to_city ?: null,
            'bill_to_state' => $this->bill_to_state ?: null,
            'bill_to_zip' => $this->bill_to_zip ?: null,
            'bill_to_country' => $this->bill_to_country ?: null,
            'price_level_id' => $this->price_level_id,
            'tax_code_id' => $this->tax_code_id,
            'terms' => $this->terms ?: null,
            'credit_limit' => $this->credit_limit !== null && $this->credit_limit !== '' ? $this->credit_limit : null,
            'online_payment_eligible' => $this->online_payment_eligible,
            'pinned_note' => $this->pinned_note ?: null,
            'is_active' => $this->is_active,
        ];

        try {
            if ($this->customer) {
                $customer = $update->handle($this->customer, $payload);
            } else {
                $customer = $create->handle($payload);
            }
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            return null;
        }

        return redirect()->route('customers.show', $customer);
    }

    public function render()
    {
        $title = $this->customer ? 'Edit Customer' : 'New Customer';

        return view('livewire.customers.customer-form', [
            'priceLevels' => PriceLevel::query()->where('is_active', true)->orderBy('name')->get(),
            'taxCodes' => TaxCode::query()->where('is_active', true)->orderBy('code')->get(),
        ])->layoutData([
            'title' => $title,
            'windowTitle' => $title,
        ]);
    }
}
