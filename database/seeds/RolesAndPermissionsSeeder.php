<?php

namespace Database\Seeders;

use App\Models\User;
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

        Permission::create(['name' => 'rgpd']);

        $readAllButRGPD = Permission::create(['name' => 'read_all_but_rgpd']);
        Role::create(['name' => 'GOS'])->givePermissionTo($readAllButRGPD);
        Role::create(['name' => 'CMED'])->givePermissionTo($readAllButRGPD);
        Role::create(['name' => 'RH'])->givePermissionTo($readAllButRGPD);

        /** @var User $user */
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@roundtraining.com',
            // 'password' is the password
        ]);
        $user->assignRole('admin');
    }
}
