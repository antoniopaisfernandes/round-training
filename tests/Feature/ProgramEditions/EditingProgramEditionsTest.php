<?php

namespace Tests\Feature\ProgramEditions;

use App\Enrollment;
use App\ProgramEdition;
use App\ProgramEditionSchedule;
use App\Student;
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
    public function it_updates_the_supplier_certifications()
    {
        $programEdition = factory(ProgramEdition::class)->create([
            'supplier_certifications' => 'OLD',
        ]);

        $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'supplier_certifications' => 'NEW',
            ]
        ));

        $this->assertDatabaseHas('program_editions', [
            'supplier_certifications' => 'NEW',
        ]);
    }

    /** @test */
    public function when_adding_schedules_to_a_program_edition_they_must_have_a_starts_at_date()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->states('without-schedules')->create()->fresh();

        $updatedProgramEdition = array_merge(
            $programEdition->toArray(),
            [
                'schedules' => [
                    []
                ],
            ]
        );

        try {
            $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->assertArrayHasKey('schedules.0.starts_at', $e->errors());
            $this->assertNull(ProgramEditionSchedule::first());
            return;
        }
        $this->fail('A validation exception should be thrown but it was not');
    }

    /** @test */
    public function it_can_remove_schedules_from_program_edition()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->states('with-2-schedules')->create();
        $this->assertCount(2, ProgramEditionSchedule::all());

        $updatedProgramEdition = array_merge(
            $programEdition->toArray(),
            [
                'schedules' => [],
            ]
        );

        $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);

        $this->assertCount(0, ProgramEditionSchedule::all());
    }

    /** @test */
    public function it_can_add_schedules_to_program_edition_with_schedules()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->states('with-2-schedules')->create()->fresh();
        $this->assertCount(2, ProgramEditionSchedule::all());
        $updatedProgramEdition = $programEdition->toArray();
        $updatedProgramEdition['schedules'][] = factory(ProgramEditionSchedule::class)->make([
            'program_edition_id' => null,
            'starts_at' => $programEdition->starts_at,
            'ends_at' => $programEdition->ends_at,
        ])->toArray();

        $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);

        $this->assertCount(3, ProgramEditionSchedule::all());
    }

    /** @test */
    public function it_can_add_students_to_program_edition_with_students()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->states('with-2-students')->create()->fresh();
        $this->assertCount(2, Enrollment::all());
        $updatedProgramEdition = $programEdition->with('students')->first()->toArray();
        $updatedProgramEdition['students'][] = factory(Student::class)->create()->toArray();

        $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);

        $this->assertCount(3, Enrollment::all());
    }

    /** @test */
    public function it_can_add_enrollments_to_program_edition_with_enrollments()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->states('with-2-students')->create()->fresh();
        $this->assertCount(2, Enrollment::all());
        $updatedProgramEdition = $programEdition->with('enrollments')->first()->toArray();
        unset($updatedProgramEdition['students']); // just to make sure
        $updatedProgramEdition['enrollments'][] = factory(Enrollment::class)->create([
            'program_edition_id' => $programEdition->id,
        ])->toArray();

        $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);

        $this->assertCount(3, Enrollment::all());
    }
}
