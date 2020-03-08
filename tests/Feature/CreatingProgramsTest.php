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

        $program = [
            'name' => 'A test program',
        ];

        $response = $this->post('/program', $program);

        $response->assertRedirect();
        $this->assertDatabaseHas('programs', $program);
    }
}
