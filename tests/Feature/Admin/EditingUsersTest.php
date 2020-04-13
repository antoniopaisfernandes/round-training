<?php

namespace Tests\Feature\Admin;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ]);

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
}
