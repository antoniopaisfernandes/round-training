<?php

namespace Tests\Feature\Enrollment;

use App\Enrollment;
use App\ProgramEdition;
use App\Student;
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
}
