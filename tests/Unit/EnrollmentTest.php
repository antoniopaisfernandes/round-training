<?php

namespace Tests\Unit;

use App\Models\Enrollment;
use App\Exceptions\CannotEnrollStudentException;
use App\Models\ProgramEdition;
use App\Models\ProgramEditionSchedule;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_enroll_students()
    {
        $programEdition = ProgramEdition::factory()->create();
        $students = Student::factory()->times(2)->create();

        $programEdition->enroll($students);

        $this->assertEquals(
            $students->pluck('name', 'id'),
            $programEdition->students->pluck('name', 'id')
        );
    }

    /** @test */
    public function it_can_re_enroll_already_enrolled_students()
    {
        $programEdition = ProgramEdition::factory()->create();
        $student1 = Student::factory()->create();
        $programEdition->enroll($student1);

        $newApplicants = collect([
            $student1,
            Student::factory()->create(),
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
        $firstProgramEdition = ProgramEdition::factory()->create();
        $secondProgramEdition = ProgramEdition::factory()->create();
        $student = Student::factory()->create();

        $student->enroll($firstProgramEdition);
        $student->enroll($secondProgramEdition);

        $this->assertCount(2, Enrollment::all());
    }

    /** @test */
    public function an_enrollment_has_minutes_attended()
    {
        $student = Student::factory()->create();
        $programEdition = ProgramEditionSchedule::factory()->create([
            'program_edition_id' => ProgramEdition::factory()->create([
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
        $student = Student::factory()->create();
        $programEdition = ProgramEditionSchedule::factory()->create([
            'program_edition_id' => ProgramEdition::factory()->create([
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

    /** @test */
    public function it_cannot_enroll_for_more_than_the_number_of_minutes_of_the_edition()
    {
        $student = Student::factory()->create();
        $programEdition = ProgramEditionSchedule::factory()->create([
            'program_edition_id' => ProgramEdition::factory()->create([
                'starts_at' => '2020-05-10',
                'ends_at' => '2020-05-10',
            ])->id,
            'starts_at' => '2020-05-10 09:00:00',
            'ends_at' => '2020-05-10 10:00:00',
        ])->programEdition;

        $this->expectException(CannotEnrollStudentException::class);

        $student->enroll($programEdition, [
            'minutes_attended' => 999,
        ]);
    }

    /** @test */
    public function it_can_set_hours_attended_and_have_them_converted_to_minutes()
    {
        $enrollment = Enrollment::make([
            'hours_attended' => 1,
        ]);

        $this->assertEquals(60, $enrollment->minutes_attended);
    }

    /** @test */
    public function it_can_get_hours_attended_attribute()
    {
        $enrollment = Enrollment::make([
            'minutes_attended' => 60,
        ]);

        $this->assertEquals(1, $enrollment->hours_attended);
    }
}
