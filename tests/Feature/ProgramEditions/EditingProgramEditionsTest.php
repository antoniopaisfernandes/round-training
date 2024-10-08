<?php

namespace Tests\Feature\ProgramEditions;

use App\Models\Enrollment;
use App\Models\ProgramEdition;
use App\Models\ProgramEditionSchedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditingProgramEditionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_can_edit_a_program_edition()
    {
        $this->withoutExceptionHandling();

        $programEdition = ProgramEdition::factory()->create([
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
        $programEdition = ProgramEdition::factory()->create([
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
    public function the_owner_can_edit_the_program_edition()
    {
        $this->withoutExceptionHandling();

        $owner = User::factory()->create();
        $programEdition = ProgramEdition::factory()->create([
            'name' => 'original',
            'created_by' => $owner->id,
        ]);

        $programEdition->name = 'new name';
        $this->actingAs($owner)
            ->patch(
                "/program-editions/{$programEdition->id}",
                $programEdition->getAttributes()
            )
            ->assertOk();

        $this->assertDatabaseHas('program_editions', ['name' => 'new name']);
    }

    /** @test */
    public function other_users_besides_admin_and_owner_cannot_edit_a_program_edition()
    {
        $otherUser = User::factory()->create();
        $programEdition = ProgramEdition::factory()->create([
            'name' => 'original',
        ]);

        $response = $this->actingAs($otherUser)->patch("/program-editions/{$programEdition->id}", [
            'name' => 'new name',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('program_editions', ['name' => 'original']);
    }

    /** @test */
    public function a_supplier_is_required_for_a_program_edition()
    {
        $programEdition = ProgramEdition::factory()->create([
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
        $programEdition = ProgramEdition::factory()->create();

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
        $programEdition = ProgramEdition::factory()->create();

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
        $programEdition = ProgramEdition::factory()->create([
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
        $programEdition = ProgramEdition::factory()->create([
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
    public function it_updates_the_evaluation_notification_date()
    {
        $programEdition = ProgramEdition::factory()->create([
            'evaluation_notification_date' => today()->addMonths(3),
        ]);

        $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'evaluation_notification_date' => today()->addMonths(6),
            ]
        ));

        $this->assertDatabaseHas('program_editions', [
            'evaluation_notification_date' => today()->addMonths(6),
        ]);
    }

    /** @test */
    public function it_updates_the_goals()
    {
        $programEdition = ProgramEdition::factory()->create([
            'goals' => 'The students must be great!',
        ]);

        $this->patch("/program-editions/{$programEdition->id}", array_merge(
            $programEdition->toArray(),
            [
                'goals' => 'They will be great!',
            ]
        ));

        $this->assertDatabaseHas('program_editions', [
            'goals' => 'They will be great!',
        ]);
    }

    /** @test */
    public function when_adding_schedules_to_a_program_edition_they_must_have_a_starts_at_date()
    {
        $this->withoutExceptionHandling();

        $programEdition = ProgramEdition::factory()->withoutSchedules()->create()->fresh();

        $updatedProgramEdition = array_merge(
            $programEdition->toArray(),
            [
                'schedules' => [
                    [],
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

        $programEdition = ProgramEdition::factory()->withSchedules(2)->create();
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

        $programEdition = ProgramEdition::factory()->withSchedules(2)->create()->fresh();
        $this->assertCount(2, ProgramEditionSchedule::all());
        $updatedProgramEdition = $programEdition->toArray();
        $updatedProgramEdition['schedules'][] = ProgramEditionSchedule::factory()->make([
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

        $programEdition = ProgramEdition::factory()->withStudents(2)->create()->fresh();
        $this->assertCount(2, Enrollment::all());
        $updatedProgramEdition = $programEdition->with('students')->first()->toArray();
        $updatedProgramEdition['students'][] = Student::factory()->create()->toArray();

        $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);

        $this->assertCount(3, Enrollment::all());
    }

    /** @test */
    public function it_can_add_enrollments_to_program_edition_with_enrollments()
    {
        $this->withoutExceptionHandling();

        $programEdition = ProgramEdition::factory()->withStudents(2)->create()->fresh();
        $this->assertCount(2, Enrollment::all());
        $updatedProgramEdition = $programEdition->with('enrollments')->first()->toArray();
        unset($updatedProgramEdition['students']); // just to make sure
        $updatedProgramEdition['enrollments'][] = Enrollment::factory()->create([
            'program_edition_id' => $programEdition->id,
        ])->toArray();

        $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);

        $this->assertCount(3, Enrollment::all());
    }

    /** @test */
    public function when_updating_a_program_edition_with_enrollments_do_not_destroy_data()
    {
        $enrollmentAttributes = [
            'global_evaluation' => 'Very good',
            'evaluation_comments' => 'I liked',
            'program_should_be_repeated' => true,
            'should_be_repeated_in_months' => 6,
        ];
        $programEdition = ProgramEdition::factory()->withStudents(1)->create();
        Enrollment::first()->fill($enrollmentAttributes)->save();
        $this->assertDatabaseHas('enrollments', $enrollmentAttributes);

        $updatedProgramEdition = $programEdition->with('enrollments')->first()->toArray();
        $this->patch("/program-editions/{$programEdition->id}", $updatedProgramEdition);

        $this->assertDatabaseHas('enrollments', $enrollmentAttributes);
    }
}
