<?php

namespace App\Policies;

use App\Models\Timetable;
use App\Models\User;

class TimetablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Timetable');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Timetable $timetable): bool
    {
        return $user->checkPermissionTo('view Timetable');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Timetable');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Timetable $timetable): bool
    {
        return $user->checkPermissionTo('update Timetable');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Timetable $timetable): bool
    {
        return $user->checkPermissionTo('delete Timetable');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Timetable $timetable): bool
    {
        return $user->checkPermissionTo('restore Timetable');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Timetable $timetable): bool
    {
        return $user->checkPermissionTo('force-delete Timetable');
    }
}
