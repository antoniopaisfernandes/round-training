<?php

namespace Tests\Feature\Programs;

use App\Models\Program;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditingProgramsTest extends TestCase
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
    public function it_can_edit_a_program()
    {
        $this->withoutExceptionHandling();

        $program = Program::factory()->create([
            'name' => 'Old name',
        ]);

        $this->actingAs($this->user)->patch("/programs/{$program->id}", [
            'name' => 'New name',
        ]);

        $this->assertDatabaseHas('programs', [
            'name' => 'New name',
        ]);
    }

    /** @test */
    public function a_name_is_required_updating_a_program()
    {
        $program = Program::factory()->create([
            'name' => 'Old name',
        ]);

        $response = $this->actingAs($this->user)->patch("/programs/{$program->id}", [
            'name' => null,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('programs', [
            'name' => 'Old name',
        ]);
    }

    /** @test */
    public function a_guest_cannot_updating_a_program()
    {
        $program = Program::factory()->create([
            'name' => 'Old name',
        ]);

        $this->patch("/programs/{$program->id}", [
            'name' => null,
        ]);

        $this->assertDatabaseHas('programs', [
            'name' => 'Old name',
        ]);
    }
}
