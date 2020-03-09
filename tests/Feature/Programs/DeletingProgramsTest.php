<?php

namespace Tests\Feature\Programs;

use App\Program;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingProgramsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_can_delete_a_program()
    {
        $program = factory(Program::class)->create();

        $this->assertCount(1, Program::all());

        $this->actingAs($this->user)->delete("/program/{$program->id}");

        $this->assertCount(0, Program::all());
    }

    /** @test */
    public function it_requires_necessary_permissions_to_delete_a_program()
    {
        $this->markTestSkipped();

        $program = factory(Program::class)->create();

        $this->assertCount(1, Program::all());

        $this->actingAs($this->user)->delete("/program/{$program->id}")->assertStatus(403);

        $this->assertCount(1, Program::all());
    }
}
