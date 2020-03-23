<?php

namespace Tests\Feature\ProgramEditions;

use App\Company;
use App\Program;
use App\ProgramEdition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingProgramEditionsTest extends TestCase
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
    public function it_can_create_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition()->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function a_supplier_is_required_for_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition([
            'supplier' => null,
        ])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertSessionHasErrors(['supplier']);
    }

    /** @test */
    public function an_existing_company_is_required_for_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition([
            'company_id' => 100,
        ])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertSessionHasErrors(['company_id']);
    }

    /** @test */
    public function an_existing_program_is_required_for_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition([
            'program_id' => 100,
        ])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertSessionHasErrors(['program_id']);
    }

    /** @test */
    public function a_guest_cannot_create_a_program_edition()
    {
        auth()->logout();
        $programEdition = factory(ProgramEdition::class)->make()->toArray();

        $this->post('/program-editions', $programEdition);

        $this->assertDatabaseMissing('program_editions', $programEdition);
        $this->assertCount(0, ProgramEdition::all());
    }

    private function makeValidProgramEdition($attributes = [], $count = null)
    {
        return factory(ProgramEdition::class, $count)->make(
            array_merge(
                [
                    'program_id' => factory(Program::class)->create()->id,
                    'company_id' => factory(Company::class)->create()->id,
                    'created_by' => auth()->user()->id,
                ],
                $attributes
            )
        );
    }
}
