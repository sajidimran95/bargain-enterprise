<?php

namespace App\Actions\Vendors;

use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DuplicateVendorAction
{
    public function handle(Vendor $vendor): Vendor
    {
        return DB::transaction(function () use ($vendor) {
            $copy = $vendor->replicate([
                'vendor_number',
                'balance',
            ]);
            $copy->display_name = $vendor->display_name.' (Copy)';
            $copy->company_name = $vendor->company_name.' (Copy)';
            $copy->vendor_number = null;
            $copy->balance = 0;
            $copy->is_active = true;
            $copy->save();

            foreach ($vendor->contacts as $contact) {
                $copy->contacts()->create($contact->only([
                    'name', 'title', 'email', 'phone', 'is_primary',
                ]));
            }

            return $copy->refresh();
        });
    }
}
