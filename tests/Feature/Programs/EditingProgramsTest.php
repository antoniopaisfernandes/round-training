<?php

namespace Tests\Feature\Programs;

use App\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditingProgramsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->user = $this->createAdminUser();
    }

    /** @test */
    public function it_can_edit_a_program()
    {
        $this->withoutExceptionHandling();

        $program = factory(Program::class)->create([
            'name' => 'Old name',
        ]);

        $this->actingAs($this->user)->patch("/program/{$program->id}", [
            'name' => 'New name',
        ]);

        $this->assertDatabaseHas('programs', [
            'name' => 'New name',
        ]);
    }

    /** @test */
    public function a_name_is_required_updating_a_program()
    {
        $program = factory(Program::class)->create([
            'name' => 'Old name',
        ]);

        $response = $this->actingAs($this->user)->patch("/program/{$program->id}", [
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
        $program = factory(Program::class)->create([
            'name' => 'Old name',
        ]);

        $this->patch("/program/{$program->id}", [
            'name' => null,
        ]);

        $this->assertDatabaseHas('programs', [
            'name' => 'Old name',
        ]);
    }
}
