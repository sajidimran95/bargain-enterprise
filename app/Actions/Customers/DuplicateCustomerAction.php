<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DuplicateCustomerAction
{
    public function handle(Customer $customer): Customer
    {
        return DB::transaction(function () use ($customer) {
            $copy = $customer->replicate([
                'customer_number',
                'balance',
            ]);
            $copy->display_name = $customer->display_name.' (Copy)';
            $copy->company_name = $customer->company_name.' (Copy)';
            $copy->customer_number = null;
            $copy->balance = 0;
            $copy->is_active = true;
            $copy->save();

            foreach ($customer->contacts as $contact) {
                $copy->contacts()->create($contact->only([
                    'name', 'title', 'email', 'phone', 'is_primary',
                ]));
            }

            return $copy->refresh();
        });
    }
}
