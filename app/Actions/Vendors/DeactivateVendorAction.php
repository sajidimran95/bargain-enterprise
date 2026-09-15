<?php

namespace App\Actions\Vendors;

use App\Models\Vendor;
use Illuminate\Support\Facades\DB;

class DeactivateVendorAction
{
    public function handle(Vendor $vendor): Vendor
    {
        return DB::transaction(function () use ($vendor) {
            $vendor->update(['is_active' => false]);

            return $vendor->refresh();
        });
    }
}
