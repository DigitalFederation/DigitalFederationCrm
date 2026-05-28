<?php

namespace Domain\Individuals\Actions;

use Domain\Individuals\Models\Individual;
use Illuminate\Database\Eloquent\Model;

class AssociateIndividualToProfessionalRoleAction
{
    public function __invoke(Individual|Model $individual, int|array $professional_role_id): Individual
    {
        $individual->professionalRoles()->attach($professional_role_id);

        return $individual;
    }
}
