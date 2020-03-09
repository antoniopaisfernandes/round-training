<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'admin'])->givePermissionTo(
            Permission::create(['name' => '*'])
        );

        $readAll = Permission::create(['name' => 'read_all']);
        Role::create(['name' => 'GOS'])->givePermissionTo($readAll);
        Role::create(['name' => 'CMED'])->givePermissionTo($readAll);
        Role::create(['name' => 'RH'])->givePermissionTo($readAll);
    }
}
