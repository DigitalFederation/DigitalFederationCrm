<?php

declare(strict_types=1);

namespace Domain\Entities\Actions;

use Domain\DivingLogs\Models\DivingLocation;
use Domain\Entities\Models\Entity;

class SyncEntityFeaturedLocationsAction
{
    public function __invoke(Entity $entity, array $locationIds): void
    {
        $validIds = DivingLocation::whereIn('id', $locationIds)->pluck('id')->toArray();

        $entity->featuredDivingLocations()->sync($validIds);
    }
}
