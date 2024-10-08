<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DeletingUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->be($this->user = $this->createAdminUser());
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $this->assertCount(2, User::all());

        $this->delete("/admin/users/{$user->id}");

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_guest_cannot_delete_a_user()
    {
        $this->withExceptionHandling();

        $this->assertCount(1, User::all());
        $user = User::first();

        Auth::logout();
        $this->delete("/admin/users/{$user->id}");

        $this->assertCount(1, User::all());
    }

    /** @test */
    public function a_admin_user_cannot_delete_itself()
    {
        $this->withExceptionHandling();

        $this->assertCount(1, User::all());

        $this->delete("/admin/users/{$this->user->id}")->assertStatus(403);

        $this->assertCount(1, User::all());
    }
}
