<?php

namespace Tests\Feature\Programs;

use App\Program;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingProgramsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /** @test */
    public function it_can_create_a_program()
    {
        $this->withoutExceptionHandling();

        $program = $this->validProgram();

        $response = $this->actingAs($this->user)->post('/program', $program);

        $response->assertRedirect();
        $this->assertDatabaseHas('programs', $program);
    }

    /** @test */
    public function a_name_is_required_for_a_program()
    {
        $program = $this->validProgram([
            'name' => null,
        ]);

        $response = $this->actingAs($this->user)->post('/program', $program);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function a_guest_cannot_create_a_program()
    {
        $program = $this->validProgram();

        $this->post('/program', $program);

        $this->assertDatabaseMissing('programs', $program);
        $this->assertCount(0, Program::all());
    }

    private function validProgram($overrides = [])
    {
        return array_merge([
            'name' => 'A test program',
        ], $overrides);
    }
}