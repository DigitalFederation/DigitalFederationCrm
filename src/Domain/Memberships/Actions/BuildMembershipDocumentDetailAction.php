<?php

namespace Domain\Memberships\Actions;

use Domain\Documents\DataTransferObject\DocumentDetailData;
use Domain\Memberships\Models\Membership;
use Domain\Products\Models\Product;

/**
 * @mixin \Domain\Memberships\Actions\BuildMembershipDocumentDetailAction
 */
class BuildMembershipDocumentDetailAction
{
    public function __invoke(Membership $membership): array
    {
        $membership->load('plans.products');

        $documentLines = [];

        // Determine customer name based on Membership association
        $customerName = $membership->individual?->name ?? $membership->entity?->name ?? 'Unknown Customer';

        foreach ($membership->plans as $plan) {
            $documentLines[] = DocumentDetailData::fromArray([
                'owner_id' => $membership->id,
                'owner_type' => Membership::class,
                'unit_value' => $plan->price,
                'tax_value' => $plan->tax_value,
                'tax_percentage' => $plan->tax_percentage,
                'description' => $plan->name,
                'customer_name' => $customerName,
            ]);

            foreach ($plan->products as $product) {
                $documentLines[] = DocumentDetailData::fromArray([
                    'owner_id' => $product->id,
                    'owner_type' => Product::class,
                    'unit_value' => $product->price,
                    'tax_value' => $product->tax_value,
                    'tax_percentage' => $product->tax_percentage,
                    'description' => $product->name,
                    'customer_name' => $customerName,
                ]);
            }
        }

        return $documentLines;
    }
}
