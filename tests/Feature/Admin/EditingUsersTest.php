<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class EditingUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->user = $this->createAdminUser());
    }

    /** @test */
    public function it_can_edit_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'name' => 'Old name',
        ]);

        $this->patch("/admin/users/{$user->id}", [
            'name' => 'New name',
            'email' => $user->email,
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'name' => 'New name',
        ]);
    }

    /** @test */
    public function a_name_is_required_updating_a_user()
    {
        $user = factory(User::class)->create([
            'name' => 'Old name',
        ]);

        $response = $this->patch("/admin/users/{$user->id}", [
            'name' => null,
            'email' => $user->email,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('users', [
            'name' => 'Old name',
        ]);
    }

    /** @test */
    public function an_email_is_required_updating_a_user()
    {
        $user = factory(User::class)->create([
            'email' => 'old@example.com',
        ]);

        $response = $this->patch("/admin/users/{$user->id}", [
            'name' => $user->name,
            'email' => null,
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseHas('users', [
            'email' => 'old@example.com',
        ]);
    }

    /** @test */
    public function it_can_assign_a_role_to_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->assertCount(0, $user->roles);

        $this->patch("/admin/users/{$user->id}", array_merge(
            $user->toArray(),
            ['roles' => [
                [
                    'name' => 'admin',
                ]
            ]]
        ))->assertSuccessful();

        $this->assertCount(1, $user->fresh()->roles);
    }

    /** @test */
    public function it_can_assign_permission_to_a_user()
    {
        Permission::create(['name' => 'rgpd']);
        $user = factory(User::class)->create();
        $this->assertCount(0, $user->permissions);

        $this->patch("/admin/users/{$user->id}", array_merge(
            $user->toArray(),
            ['permissions' => [
                [
                    'name' => 'rgpd',
                ]
            ]]
        ))->assertSuccessful();

        $this->assertCount(1, $user->fresh()->permissions);
    }

    /** @test */
    public function it_cannot_remove_admin_role_if_there_is_only_one_user()
    {
        // We have the user created in the setUp method

        $this->patch("/admin/users/{$this->user->id}", array_merge(
            $this->user->toArray(),
            ['roles' => []]
        ))->assertRedirect();

        $this->assertCount(1, $this->user->fresh()->roles);
    }
}
