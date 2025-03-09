<?php

namespace App\Policies;

use App\Models\FamilyMember;
use App\Models\User;

class FamilyMemberPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any FamilyMember');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FamilyMember $familymember): bool
    {
        return $user->checkPermissionTo('view FamilyMember');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create FamilyMember');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FamilyMember $familymember): bool
    {
        return $user->checkPermissionTo('update FamilyMember');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FamilyMember $familymember): bool
    {
        return $user->checkPermissionTo('delete FamilyMember');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FamilyMember $familymember): bool
    {
        return $user->checkPermissionTo('restore FamilyMember');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FamilyMember $familymember): bool
    {
        return $user->checkPermissionTo('force-delete FamilyMember');
    }
}
