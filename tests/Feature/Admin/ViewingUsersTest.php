<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewingUsersTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->be($this->user = $this->createAdminUser());
    }

    /** @test */
    public function it_can_view_a_user()
    {
        $user = factory(User::class)->create();

        $response = $this->get("/admin/users/{$user->id}");

        $response->assertJson($user->fresh()->toArray());
    }

    /** @test */
    public function it_can_show_a_list_of_users()
    {
        $users = factory(User::class, 4)->create();

        $response = $this->getJson("/admin/users");

        $this->assertCount(
            $users->count() + 1, // Admin user created in setUp
            collect($response->json()['data'])
        );
    }

    /** @test */
    public function viewing_users_requires_admin_permission()
    {
        $this->withExceptionHandling();

        $newUser = factory(User::class)->create();

        $response = $this->actingAs($newUser)->get("/admin/users");

        $response->assertForbidden();
    }
}
