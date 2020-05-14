<?php

namespace App\Policies;

use App\ProgramEdition;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramEditionPolicy
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
     * @param  \App\ProgramEdition  $programEdition
     * @return mixed
     */
    public function view(User $user, ProgramEdition $programEdition)
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
        return $user->can('create_program_edition');
    }

    /**
     * Determine whether the user can update the program.
     *
     * @param  \App\User  $user
     * @param  \App\ProgramEdition  $programEdition
     * @return mixed
     */
    public function update(User $user, ProgramEdition $programEdition)
    {
        return $user->can('update_program_edition') || $user->is($programEdition->manager);
    }

    /**
     * Determine whether the user can delete the program.
     *
     * @param  \App\User  $user
     * @param  \App\ProgramEdition  $programEdition
     * @return mixed
     */
    public function delete(User $user, ProgramEdition $programEdition)
    {
        return $user->can('delete_program_edition');
    }

    /**
     * Determine whether the user can restore the program.
     *
     * @param  \App\User  $user
     * @param  \App\ProgramEdition  $programEdition
     * @return mixed
     */
    public function restore(User $user, ProgramEdition $programEdition)
    {
        return $user->can('delete_program_edition');
    }

    /**
     * Determine whether the user can permanently delete the program.
     *
     * @param  \App\User  $user
     * @param  \App\ProgramEdition  $programEdition
     * @return mixed
     */
    public function forceDelete(User $user, ProgramEdition $programEdition)
    {
        return $user->can('delete_program_edition');
    }
}
