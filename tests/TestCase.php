<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createAdminUser($overrides = []) : User
    {
        $role = Role::updateOrCreate(['name' => 'admin']);
        $permission = Permission::updateOrCreate(['name' => '*']);
        $role->givePermissionTo($permission);

        return factory(User::class)->create($overrides)->assignRole($role);
    }
}
