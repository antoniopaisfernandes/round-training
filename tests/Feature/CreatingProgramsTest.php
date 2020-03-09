<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingProgramsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_program()
    {
        $this->withoutExceptionHandling();

        $program = $this->validProgram();

        $response = $this->post('/program', $program);

        $response->assertRedirect();
        $this->assertDatabaseHas('programs', $program);
    }

    /** @test */
    public function a_name_is_required_for_a_program()
    {
        $program = $this->validProgram([
            'name' => null,
        ]);

        $response = $this->post('/program', $program);

        $response->assertSessionHasErrors(['name']);
        $response->assertRedirect('/program');
    }
    private function validProgram($overrides = [])
    {
        return array_merge([
            'name' => 'A test program',
        ], $overrides);
    }
}
