<?php

namespace Tests\Feature\Programs;

use App\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingProgramsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = $this->createAdminUser();
    }

    /** @test */
    public function it_can_create_a_program()
    {
        $this->withoutExceptionHandling();

        $program = factory(Program::class)->make()->toArray();

        $response = $this->actingAs($this->user)->post('/program', $program);

        $response->assertRedirect();
        $this->assertDatabaseHas('programs', $program);
    }

    /** @test */
    public function a_name_is_required_for_a_program()
    {
        $program = factory(Program::class)->make([
            'name' => null,
        ])->toArray();

        $response = $this->actingAs($this->user)->post('/program', $program);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function a_guest_cannot_create_a_program()
    {
        $program = factory(Program::class)->make()->toArray();

        $this->post('/program', $program);

        $this->assertDatabaseMissing('programs', $program);
        $this->assertCount(0, Program::all());
    }
}
