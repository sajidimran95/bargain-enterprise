<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class ActivateCustomerAction
{
    public function handle(Customer $customer): Customer
    {
        return DB::transaction(function () use ($customer) {
            $customer->update(['is_active' => true]);

            return $customer->refresh();
        });
    }
}
