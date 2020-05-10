<?php

namespace Tests\Unit;

use App\Enrollment;
use App\ProgramEdition;
use App\ProgramEditionSchedule;
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

    /** @test */
    public function an_enrollment_has_minutes_attended()
    {
        $student = factory(Student::class)->create();
        $programEdition = factory(ProgramEditionSchedule::class)->create([
            'program_edition_id' => factory(ProgramEdition::class)->create([
                'starts_at' => '2020-05-10',
                'ends_at' => '2020-05-10',
            ])->id,
            'starts_at' => '2020-05-10 09:00:00',
            'ends_at' => '2020-05-10 10:00:00',
        ])->programEdition;

        $student->enroll($programEdition);

        $this->assertEquals(60, Enrollment::first()->minutes_attended);
    }

    /** @test */
    public function an_enrollment_has_specificiy_of_minutes_attendance_other_than_the_program_edition()
    {
        $student = factory(Student::class)->create();
        $programEdition = factory(ProgramEditionSchedule::class)->create([
            'program_edition_id' => factory(ProgramEdition::class)->create([
                'starts_at' => '2020-05-10',
                'ends_at' => '2020-05-10',
            ])->id,
            'starts_at' => '2020-05-10 09:00:00',
            'ends_at' => '2020-05-10 10:00:00',
        ])->programEdition;

        $student->enroll($programEdition, [
            'minutes_attended' => 30,
        ]);

        $this->assertEquals(30, Enrollment::first()->minutes_attended);
    }
}
