<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EnsureAtLeastOneAdmin implements Rule
{
    protected $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // We're only checking when removing admin roles
        if (collect($value)->pluck('name')->contains('admin')) {
            return true;
        }

        $assignedRolesTable = config('permission.table_names.model_has_roles');
        $rolesTable = config('permission.table_names.roles');
        return DB::table($assignedRolesTable)
            ->join($rolesTable, "{$rolesTable}.id", '=', "{$assignedRolesTable}.role_id")
            ->where("{$rolesTable}.name", 'admin')
            ->where("{$assignedRolesTable}.model_type", User::class)
            ->where("{$assignedRolesTable}.model_id", '<>', $this->user->id)
            ->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cannot remove admin role to the only admin in the system.';
    }
}
