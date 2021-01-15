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

class ViewingEnrollmentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_a_list_of_students_able_to_enroll_and_editable_enrollments()
    {
        $this->withoutExceptionHandling();

        // Arrange
        $pastProgramEdition = factory(ProgramEdition::class)->state('with-1-students')->create([
            'starts_at' => today()->subDays(2),
            'ends_at' => today()->subDays(1),
        ]);
        $studentEnrolledInPastProgramEdition = $pastProgramEdition->students->first();

        $currentProgramEdition = factory(ProgramEdition::class)->state('with-1-students')->create([
            'starts_at' => today(),
            'ends_at' => today()->addDays(1),
        ]);
        $studentEnrolledInCurrentProgramEdition = $currentProgramEdition->students->first();

        // Act
        $response = $this->actingAs($this->createAdminUser())
                        ->get(route('enrollments.index'))
                        ->assertOk();

        // Assert
        $response->assertViewHas('studentsAbleToEnroll');
        $this->assertCount(1, $response->viewData('studentsAbleToEnroll'));
        $this->assertTrue($response->viewData('studentsAbleToEnroll')->first()->is($studentEnrolledInPastProgramEdition));

        $response->assertViewHas('enrollmentsAbleToEdit');
        $this->assertCount(1, $response->viewData('enrollmentsAbleToEdit'));
        $this->assertTrue($response->viewData('enrollmentsAbleToEdit')->first()->student->is($studentEnrolledInCurrentProgramEdition));
    }

    /** @test */
    public function it_shows_a_list_of_students_able_to_enroll_filtered_by_access_using_the_leader_of_the_student()
    {
        $this->withoutExceptionHandling();

        // Arrange
        factory(ProgramEdition::class)->create();
        $leader = factory(User::class)->create();

        $studentFromCompanyA = factory(Student::class)->create();
        $studentFromCompanyB = factory(Student::class)->create([
            'leader_id' => $leader->id,
        ]);

        // Act
        $response = $this->actingAs($leader)
                        ->get(route('enrollments.index'))
                        ->assertOk();

        // Assert
        $response->assertViewHas('studentsAbleToEnroll');
        $this->assertCount(1, $response->viewData('studentsAbleToEnroll'));
        $this->assertTrue($response->viewData('studentsAbleToEnroll')->first()->is($studentFromCompanyB));
    }

    /** @test */
    public function it_shows_a_list_of_students_able_to_enroll_filtered_by_access_using_the_company_coordinator()
    {
        $this->withoutExceptionHandling();

        // Arrange
        factory(ProgramEdition::class)->create();
        $coordinator = factory(User::class)->create();

        $studentFromCompanyA = factory(Student::class)->create();
        $studentFromCompanyB = factory(Student::class)->create([
            'current_company_id' => factory(Company::class)->create([
                'coordinator_id' => $coordinator->id,
            ])->id,
        ]);

        // Act
        $response = $this->actingAs($coordinator)
            ->get(route('enrollments.index'))
            ->assertOk();

        // Assert
        $response->assertViewHas('studentsAbleToEnroll');
        $this->assertCount(1, $response->viewData('studentsAbleToEnroll'));
        $this->assertTrue($response->viewData('studentsAbleToEnroll')->first()->is($studentFromCompanyB));
    }
}
