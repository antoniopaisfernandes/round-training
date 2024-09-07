<?php

namespace Tests\Feature\ProgramEditions;

use App\Models\Program;
use App\Models\ProgramEdition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingProgramEditionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_program_edition()
    {
        $programEdition = ProgramEdition::factory()->create();

        $this->assertCount(1, ProgramEdition::all());

        $this->actingAs($this->createAdminUser())->delete("/program-editions/{$programEdition->id}");

        $this->assertCount(0, ProgramEdition::all());
    }

    /** @test */
    public function it_requires_necessary_permissions_to_delete_a_program()
    {
        $userWithoutPermission = User::factory()->create();
        $programEdition = ProgramEdition::factory()->create();

        $this->assertCount(1, Program::all());

        $this->actingAs($userWithoutPermission)->delete("/program-editions/{$programEdition->id}")->assertStatus(403);

        $this->assertCount(1, Program::all());
    }
}
