<?php

namespace App\Livewire\Vendors;

use App\Actions\Vendors\CreateVendorAction;
use App\Actions\Vendors\UpdateVendorAction;
use App\Models\Vendor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Vendor')]
class VendorForm extends Component
{
    use AuthorizesRequests;

    public ?Vendor $vendor = null;

    public ?string $vendor_number = '';

    public string $company_name = '';

    public string $display_name = '';

    public ?string $first_name = '';

    public ?string $last_name = '';

    public ?string $email = '';

    public ?string $phone = '';

    public ?string $fax = '';

    public ?string $bill_from_street1 = '';

    public ?string $bill_from_street2 = '';

    public ?string $bill_from_city = '';

    public ?string $bill_from_state = '';

    public ?string $bill_from_zip = '';

    public ?string $bill_from_country = '';

    public ?string $terms = '';

    public ?string $account_number = '';

    public ?string $notes = '';

    public bool $is_active = true;

    public function mount(?Vendor $vendor = null): void
    {
        if ($vendor?->exists) {
            $this->authorize('update', $vendor);
            $this->vendor = $vendor;
            $data = $vendor->only([
                'vendor_number', 'company_name', 'display_name', 'first_name', 'last_name',
                'email', 'phone', 'fax', 'bill_from_street1', 'bill_from_street2',
                'bill_from_city', 'bill_from_state', 'bill_from_zip', 'bill_from_country',
                'terms', 'account_number', 'notes', 'is_active',
            ]);

            foreach ([
                'vendor_number', 'company_name', 'display_name', 'first_name', 'last_name',
                'email', 'phone', 'fax', 'bill_from_street1', 'bill_from_street2',
                'bill_from_city', 'bill_from_state', 'bill_from_zip', 'bill_from_country',
                'terms', 'account_number', 'notes',
            ] as $stringField) {
                $data[$stringField] = (string) ($data[$stringField] ?? '');
            }

            $this->fill($data);
        } else {
            $this->authorize('create', Vendor::class);
        }
    }

    public function save(CreateVendorAction $create, UpdateVendorAction $update): mixed
    {
        $payload = [
            'vendor_number' => $this->vendor_number ?: null,
            'company_name' => $this->company_name,
            'display_name' => $this->display_name ?: $this->company_name,
            'first_name' => $this->first_name ?: null,
            'last_name' => $this->last_name ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'fax' => $this->fax ?: null,
            'bill_from_street1' => $this->bill_from_street1 ?: null,
            'bill_from_street2' => $this->bill_from_street2 ?: null,
            'bill_from_city' => $this->bill_from_city ?: null,
            'bill_from_state' => $this->bill_from_state ?: null,
            'bill_from_zip' => $this->bill_from_zip ?: null,
            'bill_from_country' => $this->bill_from_country ?: null,
            'terms' => $this->terms ?: null,
            'account_number' => $this->account_number ?: null,
            'notes' => $this->notes ?: null,
            'is_active' => $this->is_active,
        ];

        try {
            $vendor = $this->vendor
                ? $update->handle($this->vendor, $payload)
                : $create->handle($payload);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            return null;
        }

        return redirect()->route('vendors.show', $vendor);
    }

    public function render()
    {
        $title = $this->vendor ? 'Edit Vendor' : 'New Vendor';

        return view('livewire.vendors.vendor-form')->layoutData([
            'title' => $title,
            'windowTitle' => $title,
        ]);
    }
}
