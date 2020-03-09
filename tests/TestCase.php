<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createAdminUser($overrides = []) : User
    {
        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => '*']);
        $role->givePermissionTo($permission);

        return factory(User::class)->create($overrides)->assignRole($role);
    }
}
