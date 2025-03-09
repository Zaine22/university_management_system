<?php

namespace App\Policies;

use App\Models\Installment;
use App\Models\User;

class InstallmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Installment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Installment $installment): bool
    {
        return $user->checkPermissionTo('view Installment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Installment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Installment $installment): bool
    {
        return $user->checkPermissionTo('update Installment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Installment $installment): bool
    {
        return $user->checkPermissionTo('delete Installment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Installment $installment): bool
    {
        return $user->checkPermissionTo('restore Installment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Installment $installment): bool
    {
        return $user->checkPermissionTo('force-delete Installment');
    }
}
