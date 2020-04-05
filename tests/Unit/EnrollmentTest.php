<?php

namespace Tests\Unit;

use App\Enrollment;
use App\ProgramEdition;
use App\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_enroll_students()
    {
        $programEdition = factory(ProgramEdition::class)->create();
        $students = factory(Student::class, 2)->create();

        $programEdition->enroll($students);

        $this->assertEquals(
            $students->pluck('name', 'id'),
            $programEdition->students->pluck('name', 'id')
        );
    }

    /** @test */
    public function it_can_re_enroll_already_enrolled_students()
    {
        $programEdition = factory(ProgramEdition::class)->create();
        $student1 = factory(Student::class)->create();
        $programEdition->enroll($student1);

        $newApplicants = collect([
            $student1,
            factory(Student::class)->create(),
        ]);
        $programEdition->enroll($newApplicants);

        $this->assertEquals(
            $newApplicants->pluck('name', 'id'),
            $programEdition->students->pluck('name', 'id')
        );
    }

    /** @test */
    public function it_can_enroll_a_student_in_more_than_one_program_edition()
    {
        $firstProgramEdition = factory(ProgramEdition::class)->create();
        $secondProgramEdition = factory(ProgramEdition::class)->create();
        $student = factory(Student::class)->create();

        $student->enroll($firstProgramEdition);
        $student->enroll($secondProgramEdition);

        $this->assertCount(2, Enrollment::all());
    }
}
