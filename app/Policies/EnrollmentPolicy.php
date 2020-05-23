<?php

namespace App\Policies;

use App\Enrollment;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Enrollment  $enrollment
     * @return mixed
     */
    public function view(User $user, Enrollment $enrollment)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Enrollment  $enrollment
     * @return mixed
     */
    public function delete(User $user, Enrollment $enrollment)
    {
        return $user->can('delete_enrollment')
            || (
                $enrollment->programEdition->starts_at >= today()
                && $user->is($enrollment->company->coordinator)
            );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Enrollment  $enrollment
     * @return mixed
     */
    public function restore(User $user, Enrollment $enrollment)
    {
        return $this->delete($user, $enrollment);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Enrollment  $enrollment
     * @return mixed
     */
    public function forceDelete(User $user, Enrollment $enrollment)
    {
        return $this->delete($user, $enrollment);
    }
}
