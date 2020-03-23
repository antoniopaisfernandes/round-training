<?php

namespace Tests\Feature\Programs;

use App\Program;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewingProgramsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function it_can_view_a_program()
    {
        $program = factory(Program::class)->create();

        $response = $this->get("/programs/{$program->id}");

        $response->assertJson($program->fresh()->toArray());
    }

    /** @test */
    public function it_shows_a_list_of_programs()
    {
        $programs = factory(Program::class, 4)->create();

        $response = $this->actingAs($this->user)->get("/programs");

        $response->assertViewHas('programs');
        $this->assertEquals(
            $programs->fresh()->all(),
            $response->viewData('programs')->items()
        );
    }

    /** @test */
    public function it_shows_a_list_of_20_paginated_programs()
    {
        $programs = factory(Program::class, 50)->create();

        $response = $this->actingAs($this->user)->get("/programs");

        $response->assertViewHas('programs');
        $this->assertCount(
            20,
            $response->viewData('programs')->items()
        );
    }
}
