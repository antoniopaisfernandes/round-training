<?php

namespace Tests\Feature\ProgramEditions;

use App\ProgramEdition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditingProgramEditionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be(
            $this->user = $this->createAdminUser()
        );
    }

    /** @test */
    public function it_can_edit_a_program_edition()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->create([
            'teacher_name' => 'Old teacher',
        ]);

        $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'teacher_name' => 'New teacher',
            ]
        ));

        $this->assertDatabaseHas('program_editions', [
            'teacher_name' => 'New teacher',
        ]);
    }

    /** @test */
    public function a_supplier_is_required_for_a_program_edition()
    {
        $programEdition = factory(ProgramEdition::class)->create([
            'supplier' => 'Old supplier',
        ]);

        $response = $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'supplier' => null,
            ]
        ));

        $response->assertSessionHasErrors(['supplier']);
        $this->assertDatabaseHas('program_editions', [
            'supplier' => 'Old supplier',
        ]);
    }

    /** @test */
    public function an_existing_company_is_required_for_a_program_edition()
    {
        $programEdition = factory(ProgramEdition::class)->create();

        $response = $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'company_id' => 9999,
            ]
        ));

        $response->assertSessionHasErrors(['company_id']);
        $this->assertDatabaseHas('program_editions', [
            'company_id' => $programEdition->company_id,
        ]);
    }

    /** @test */
    public function an_existing_program_is_required_for_a_program_edition()
    {
        $programEdition = factory(ProgramEdition::class)->create();

        $response = $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'program_id' => 9999,
            ]
        ));

        $response->assertSessionHasErrors(['program_id']);
        $this->assertDatabaseHas('program_editions', [
            'program_id' => $programEdition->program_id,
        ]);
    }

    /** @test */
    public function it_updates_the_cost_of_a_program_edition()
    {
        $programEdition = factory(ProgramEdition::class)->create([
            'cost' => 100,
        ]);

        $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'cost' => 200,
            ]
        ));

        $this->assertDatabaseHas('program_editions', [
            'cost' => 200,
        ]);
    }

    /** @test */
    public function a_guest_cannot_updating_a_program()
    {
        auth()->logout();
        $programEdition = factory(ProgramEdition::class)->create([
            'teacher_name' => 'Old teacher_name',
        ]);

        $this->patch("/program-editions/{$programEdition->id}", [
            'teacher_name' => null,
        ]);

        $this->assertDatabaseHas('program_editions', [
            'teacher_name' => 'Old teacher_name',
        ]);
    }
}
