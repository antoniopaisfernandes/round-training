<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CreatingUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->user = $this->createAdminUser());
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $this->withoutExceptionHandling();

        $user = $this->validUser();

        $response = $this->post('/admin/users', $user);

        $response->assertOk();
        $this->assertDatabaseHas('users', ['email' => $user['email']]);
    }

    /** @test */
    public function a_name_is_required_for_a_user()
    {
        $user = $this->validUser([
            'name' => null,
        ]);

        $response = $this->post('/admin/users', $user);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function an_email_is_required_for_a_user()
    {
        $user = $this->validUser([
            'email' => null,
        ]);

        $response = $this->post('/admin/users', $user);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function a_password_is_required_for_a_user()
    {
        $user = $this->validUser([
            'password' => null,
        ]);

        $response = $this->post('/admin/users', $user);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function a_guest_cannot_create_a_user()
    {
        $this->assertCount(1, User::all());
        $user = $this->validUser();

        auth()->logout();
        $this->post('/admin/users', $user);

        $this->assertDatabaseMissing('users', $user);
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function it_can_add_users_with_their_roles()
    {
        $this->withoutExceptionHandling();

        $user = $this->validUser([
            'roles' => ['admin'],
        ]);

        $createdUser = $this->post('/admin/users', $user)->baseResponse->original;

        $this->assertCount(1, $createdUser->roles);
    }

    /** @test */
    public function it_can_add_users_with_their_permissions()
    {
        $this->withoutExceptionHandling();

        Permission::create(['name' => 'rgpd']);
        $user = $this->validUser([
            'permissions' => ['rgpd'],
        ]);

        $createdUser = $this->post('/admin/users', $user)->baseResponse->original;

        $this->assertCount(1, $createdUser->permissions);
    }

    private function validUser($attributes = []) : array
    {
        return array_merge(
            factory(User::class)->make($attributes)->toArray(),
            [
                'password' => 'new_password_to_add',
                'password_confirmation' => 'new_password_to_add',
            ],
            $attributes
        );
    }
}
