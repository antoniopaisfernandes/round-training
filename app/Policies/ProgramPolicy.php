<?php

namespace App\Policies;

use App\Program;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any programs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the program.
     *
     * @param  \App\User  $user
     * @param  \App\Program  $program
     * @return mixed
     */
    public function view(User $user, Program $program)
    {
        return true;
    }

    /**
     * Determine whether the user can create programs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_program');
    }

    /**
     * Determine whether the user can update the program.
     *
     * @param  \App\User  $user
     * @param  \App\Program  $program
     * @return mixed
     */
    public function update(User $user, Program $program)
    {
        return $user->can('update_program');
    }

    /**
     * Determine whether the user can delete the program.
     *
     * @param  \App\User  $user
     * @param  \App\Program  $program
     * @return mixed
     */
    public function delete(User $user, Program $program)
    {
        return $user->can('delete_program');
    }

    /**
     * Determine whether the user can restore the program.
     *
     * @param  \App\User  $user
     * @param  \App\Program  $program
     * @return mixed
     */
    public function restore(User $user, Program $program)
    {
        return $user->can('delete_program');
    }

    /**
     * Determine whether the user can permanently delete the program.
     *
     * @param  \App\User  $user
     * @param  \App\Program  $program
     * @return mixed
     */
    public function forceDelete(User $user, Program $program)
    {
        return $user->can('delete_program');
    }
}
