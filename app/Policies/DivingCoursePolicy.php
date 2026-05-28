<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Domain\DivingLogs\Models\DivingCourse;
use Domain\Entities\Models\Entity;
use Illuminate\Auth\Access\HandlesAuthorization;

class DivingCoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     * Allow CMAS super-admin or the Entity admin associated with the course list.
     */
    public function viewAny(User $user, ?Entity $entity = null): bool
    {
        $hasSuperAdminRole = $user->hasRole('admin');
        if ($hasSuperAdminRole) {
            return true;
        }

        $hasEntityAdminRole = $user->hasRole('entity-admin');
        $containsEntity = $entity ? $user->entities->contains($entity) : false;

        // Use contains() for clearer relationship check
        $result = $entity &&
            $hasEntityAdminRole &&
            $containsEntity;

        return $result;
    }

    /**
     * Determine whether the user can view the model.
     * Allow CMAS super-admin or the Entity admin associated with the specific course's entity.
     */
    public function view(User $user, DivingCourse $divingCourse): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check relationship via the DivingCourse's entity relation
        return $user->hasRole('entity-admin') &&
            $divingCourse->entity && // Ensure entity relationship is loaded/exists
            $user->entities->contains($divingCourse->entity);
    }

    /**
     * Determine whether the user can create models.
     * Only CMAS super-admin or an Entity admin associated with the target entity.
     */
    public function create(User $user, Entity $entity): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Use contains() for clearer relationship check
        return $user->hasRole('entity-admin') &&
            $user->entities->contains($entity);
    }

    /**
     * Determine whether the user can update the model.
     * Allow CMAS super-admin or the Entity admin associated with the specific course's entity.
     */
    public function update(User $user, DivingCourse $divingCourse): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check relationship via the DivingCourse's entity relation
        return $user->hasRole('entity-admin') &&
            $divingCourse->entity &&
            $user->entities->contains($divingCourse->entity);
    }

    /**
     * Determine whether the user can delete the model.
     * Allow CMAS super-admin or the Entity admin associated with the specific course's entity.
     */
    public function delete(User $user, DivingCourse $divingCourse): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check relationship via the DivingCourse's entity relation
        return $user->hasRole('entity-admin') &&
            $divingCourse->entity &&
            $user->entities->contains($divingCourse->entity);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DivingCourse $divingCourse): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DivingCourse $divingCourse): bool
    {
        return false;
    }
}
