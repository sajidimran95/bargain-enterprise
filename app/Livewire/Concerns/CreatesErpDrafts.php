<?php

namespace App\Livewire\Concerns;

use App\Models\Customer;
use App\Models\Vendor;
use App\Support\DocumentNumbers;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

trait CreatesErpDrafts
{
    protected function requireFirstCustomer(): Customer
    {
        $customer = Customer::query()->active()->orderBy('id')->first();

        if (! $customer) {
            throw new RuntimeException('Create a customer before adding sales documents.');
        }

        return $customer;
    }

    protected function requireFirstVendor(): Vendor
    {
        $vendor = Vendor::query()->active()->orderBy('id')->first();

        if (! $vendor) {
            throw new RuntimeException('Create a vendor before adding purchasing documents.');
        }

        return $vendor;
    }

    /**
     * @param  class-string<Model>  $modelClass
     */
    protected function nextNumber(string $modelClass, string $column, string $prefix): string
    {
        return DocumentNumbers::next($modelClass, $column, $prefix);
    }
}
