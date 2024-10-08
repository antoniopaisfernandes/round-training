<?php

namespace Tests\Feature\Programs;

use App\Models\Program;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingProgramsTest extends TestCase
{
    use RefreshDatabase;

    /** @var User|Authenticatable */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = $this->createAdminUser();
    }

    /** @test */
    public function it_can_create_a_program()
    {
        $this->withoutExceptionHandling();

        $program = Program::factory()->make()->toArray();

        $response = $this->actingAs($this->user)->post('/programs', $program);

        $response->assertOk();
        $this->assertDatabaseHas('programs', $program);
    }

    /** @test */
    public function a_name_is_required_for_a_program()
    {
        $program = Program::factory()->make([
            'name' => null,
        ])->toArray();

        $response = $this->actingAs($this->user)->post('/programs', $program);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function a_guest_cannot_create_a_program()
    {
        $program = Program::factory()->make()->toArray();

        $this->post('/programs', $program);

        $this->assertDatabaseMissing('programs', $program);
        $this->assertCount(0, Program::all());
    }
}
