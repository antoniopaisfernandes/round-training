<?php

namespace Tests\Feature\Enrollment;

use App\Models\Company;
use App\Models\Enrollment;
use App\Models\ProgramEdition;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_coordinator_can_enroll_its_students()
    {
        [$student, $programEdition, $coordinator_id] = $this->createStudentAndProgramEdition();

        $attributes = [
            'student_id' => $student->id,
            'program_edition_id' => $programEdition->id,
            'company_id' => $student->current_company_id,
        ];
        $response = $this->actingAs(User::find($coordinator_id))->post('/enrollments', $attributes);

        $response->assertOk();
        $this->assertDatabaseHas('enrollments', $attributes);
    }

    /** @test */
    public function when_a_coordinator_enrolls_a_student_if_the_company_is_not_supplied_derive_it_from_the_current()
    {
        [$student, $programEdition, $coordinator_id] = $this->createStudentAndProgramEdition();

        $attributes = [
            'student_id' => $student->id,
            'program_edition_id' => $programEdition->id,
        ];
        $this->actingAs(User::find($coordinator_id))->post('/enrollments', $attributes);

        $this->assertDatabaseHas('enrollments', array_merge(
            $attributes,
            [
                'company_id' => $student->current_company_id,
            ]
        ));
    }

    /** @test */
    public function it_requires_a_valid_student_id_when_enrolling()
    {
        $attributes = [
            'student_id' => 999,
            'program_edition_id' => ProgramEdition::factory()->create()->id,
            'company_id' => Company::factory()->create()->id,
        ];
        $this->assertEquals(0, Enrollment::count());

        $response = $this->actingAs($this->createAdminUser())->post('/enrollments', $attributes);

        $response->assertSessionHasErrors(['student_id']);
        $this->assertEquals(0, Enrollment::count());
    }

    /** @test */
    public function only_admins_and_coordinators_can_enroll_students()
    {
        [$student, $programEdition, $coordinator_id] = $this->createStudentAndProgramEdition();

        $attributes = [
            'student_id' => $student->id,
            'program_edition_id' => $programEdition->id,
            'company_id' => $student->current_company_id,
        ];
        $response = $this->actingAs(User::factory()->create())->post('/enrollments', $attributes);

        $response->assertForbidden();
        $this->assertEquals(0, Enrollment::count());
    }

    /** @test */
    public function after_a_program_edition_starts_only_admins_can_enroll_students()
    {
        [$student, $programEdition, $coordinator_id] = $this->createStudentAndProgramEdition();

        /** @var ProgramEdition $programEdition */
        $programEdition->fill([
            'starts_at' => today()->subDay(),
        ])->save();

        $attributes = [
            'student_id' => $student->id,
            'program_edition_id' => $programEdition->id,
            'company_id' => $student->current_company_id,
        ];

        $this->actingAs(User::find($coordinator_id))->post('/enrollments', $attributes)->assertStatus(403);
        $this->assertCount(0, Enrollment::all());

        $this->actingAs($this->createAdminUser())->post('/enrollments', $attributes)->assertOk();
        $this->assertCount(1, Enrollment::all());
    }

    private function createStudentAndProgramEdition() : array
    {
        $student = Student::factory()->create([
            'current_company_id' => Company::factory()->create([
                'coordinator_id' => $coordinator_id = User::factory()->create()->id,
            ])->id,
        ]);
        $programEdition = ProgramEdition::factory()->create();

        return [
            $student,
            $programEdition,
            $coordinator_id,
        ];
    }
}
