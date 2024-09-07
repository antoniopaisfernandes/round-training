<?php

namespace Tests\Feature\ProgramEditions;

use App\Models\Company;
use App\Models\Program;
use App\Models\ProgramEdition;
use App\Models\ProgramEditionSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingProgramEditionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_can_create_a_program_edition()
    {
        $programEdition = $this->makeValidProgramEdition()->getAttributes();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function a_guest_cannot_create_a_program_edition()
    {
        auth()->logout();
        $programEdition = ProgramEdition::factory()->make()->setAppends([])->toArray();

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
        ])->getAttributes();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function it_creates_with_the_supplier_certifications()
    {
        $programEdition = $this->makeValidProgramEdition([
            'supplier_certifications' => 'HiTec Management Certification',
        ])->getAttributes();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function it_creates_with_the_evaluation_notification_date()
    {
        $programEdition = $this->makeValidProgramEdition([
            'evaluation_notification_date' => today()->addMonths(3),
        ])->getAttributes();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function it_creates_with_the_goals()
    {
        $programEdition = $this->makeValidProgramEdition([
            'goals' => 'The students must be great!',
        ])->getAttributes();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertDatabaseHas('program_editions', $programEdition);
    }

    /** @test */
    public function it_creates_program_edition_with_schedules()
    {
        $programEdition = ProgramEdition::factory()->withSchedules(3)->make()->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertNotNull($created = ProgramEdition::first());
        $this->assertCount(3, $created->schedules);
    }

    /** @test */
    public function when_creating_program_edition_with_schedules_they_must_have_a_starts_at_date()
    {
        $this->withoutExceptionHandling();

        $programEdition = ProgramEdition::factory()->make([
            'schedules' => [
                ProgramEditionSchedule::factory()->make([
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

        $programEdition = ProgramEdition::factory()->withStudents(3)->make()->toArray();

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertNotNull($created = ProgramEdition::first());
        $this->assertCount(3, $created->students);
    }

    /** @test */
    public function it_creates_program_edition_with_enrollments()
    {
        $this->withoutExceptionHandling();

        $programEdition = ProgramEdition::factory()->withStudents(3)->make()->toArray();
        unset($programEdition['students']); // just to make sure

        $response = $this->post('/program-editions', $programEdition);

        $response->assertOk();
        $this->assertNotNull($created = ProgramEdition::first());
        $this->assertCount(3, $created->enrollments);
    }

    private function makeValidProgramEdition($attributes = [])
    {
        return ProgramEdition::factory()->make(
            array_merge(
                [
                    'program_id' => Program::factory()->create()->id,
                    'name' => 'Edition '.mt_rand(1, 9999),
                    'company_id' => Company::factory()->create()->id,
                    'created_by' => auth()->user()->id,
                ],
                $attributes
            )
        );
    }
}
