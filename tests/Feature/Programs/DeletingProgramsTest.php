<?php

namespace Tests\Feature\Programs;

use App\Program;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingProgramsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_program()
    {
        $program = factory(Program::class)->create();

        $this->assertCount(1, Program::all());

        $this->actingAs($this->createAdminUser())->delete("/program/{$program->id}");

        $this->assertCount(0, Program::all());
    }

    /** @test */
    public function it_requires_necessary_permissions_to_delete_a_program()
    {
        $userWithoutPermission = factory(User::class)->create();
        $program = factory(Program::class)->create();

        $this->assertCount(1, Program::all());

        $this->actingAs($userWithoutPermission)->delete("/program/{$program->id}")->assertStatus(403);

        $this->assertCount(1, Program::all());
    }
}
