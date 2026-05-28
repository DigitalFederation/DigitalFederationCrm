<?php

namespace Domain\Memberships\Actions;

use Domain\Memberships\DataTransferObject\PackagePricingData;
use Domain\Memberships\Models\PackagePricing;
use Illuminate\Support\Facades\DB;

class CreatePackagePricingAction
{
    public function __invoke(PackagePricingData $data): PackagePricing
    {
        return DB::transaction(function () use ($data) {
            return PackagePricing::create([
                'membership_package_id' => $data->membership_package_id,
                'name' => $data->name,
                'duration' => $data->duration,
                'duration_unit' => $data->duration_unit,
                'price' => $data->price,
            ]);
        });
    }
}
