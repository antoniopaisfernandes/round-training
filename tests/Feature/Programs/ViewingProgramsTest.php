<?php

namespace Tests\Feature\Programs;

use App\Models\Program;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewingProgramsTest extends TestCase
{
    use RefreshDatabase;

    /** @var User|Authenticatable */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->user = User::factory()->create());
    }

    public function it_can_view_a_program()
    {
        $program = Program::factory()->create();

        $response = $this->get("/programs/{$program->id}");

        $response->assertJson($program->fresh()->toArray());
    }

    /** @test */
    public function it_shows_a_list_of_programs()
    {
        $programs = Program::factory()->times(4)->create();

        $response = $this->get("/programs");

        $this->assertEquals(
            $programs->fresh()->toArray(),
            collect($response->json())->sortBy('id')->values()->all()
        );
    }

    /** @test */
    public function the_programs_index_method_does_not_paginate()
    {
        Program::factory()->times(51)->create();

        $response = $this->get("/programs");

        $this->assertCount(
            51,
            $response->json()
        );
    }
}
