<?php

namespace Tests\Feature\ProgramEditions;

use App\Company;
use App\Program;
use App\ProgramEdition;
use App\ProgramEditionSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingProgramEditionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_can_create_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition()->setAppends([])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function a_guest_cannot_create_a_program_edition()
    {
        auth()->logout();
        $programEdition = factory(ProgramEdition::class)->make()->setAppends([])->toArray();

        $this->post('/program-editions', $programEdition);

        $this->assertDatabaseMissing('program_editions', $programEdition);
        $this->assertCount(0, ProgramEdition::all());
    }

    /** @test */
    public function a_supplier_is_required_for_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition([
            'supplier' => null,
        ])->setAppends([])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertSessionHasErrors(['supplier']);
    }

    /** @test */
    public function an_existing_company_is_required_for_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition([
            'company_id' => 100,
        ])->setAppends([])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertSessionHasErrors(['company_id']);
    }

    /** @test */
    public function an_existing_program_is_required_for_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition([
            'program_id' => 100,
        ])->setAppends([])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertSessionHasErrors(['program_id']);
    }

    /** @test */
    public function it_creates_with_the_cost_of_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition([
            'cost' => 100,
        ])->setAppends([])->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function it_creates_program_edition_with_schedules()
    {
        $programEdition = factory(ProgramEdition::class)->states('with-3-schedules')->make()->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertNotNull($created = ProgramEdition::first());
        $this->assertCount(3, $created->schedules);
    }

    /** @test */
    public function when_creating_program_edition_with_schedules_they_must_have_a_starts_at_date()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->make([
            'schedules' => [
                factory(ProgramEditionSchedule::class)->make([
                    'program_edition_id' => null,
                    'starts_at' => null,
                ])->toArray(),
            ],
        ])->toArray();

        try {
            $this->post('/program-editions', $programEdition);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('schedules.0.starts_at', $e->errors());
            $this->assertNull(ProgramEdition::first());
            return;
        }
        $this->fail('A validation exception should be thrown but it was not');
    }

    /** @test */
    public function it_creates_program_edition_with_students()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->states('with-3-students')->make()->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertNotNull($created = ProgramEdition::first());
        $this->assertCount(3, $created->students);
    }

    private function makeValidProgramEdition($attributes = [], $count = null)
    {
        return factory(ProgramEdition::class, $count)->make(
            array_merge(
                [
                    'program_id' => factory(Program::class)->create()->id,
                    'name' => 'Edition ' . mt_rand(1, 9999),
                    'company_id' => factory(Company::class)->create()->id,
                    'created_by' => auth()->user()->id,
                ],
                $attributes
            )
        );
    }
}
