<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\ProgramEdition;
use App\Models\ProgramEditionSchedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramEdition>
 */
class ProgramEditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'program_id' => Program::factory(),
            'name' => 'Edition '.mt_rand(1, 9999),
            'company_id' => Company::factory(),
            'cost' => mt_rand(1, 9999),
            'supplier' => $this->faker->company,
            'teacher_name' => $this->faker->name,
            'starts_at' => now(),
            'ends_at' => now()->addDay(),
            'created_by' => fn () => auth()->id() ?: User::factory()->create()->id,
        ];
    }

    public function withSchedules(int $count)
    {
        return $this->afterCreating(function (ProgramEdition $programEdition) use ($count) {
            ProgramEditionSchedule::factory($count)->create([
                'program_edition_id' => $programEdition->id,
            ]);
        })->afterMaking(function (ProgramEdition $programEdition) use ($count) {
            $programEdition->setRelation('schedules', ProgramEditionSchedule::factory($count)->make([
                'program_edition_id' => $programEdition->id,
            ]));
        });
    }

    public function withoutSchedules()
    {
        return $this->state(fn (array $attributes) => []);
    }

    public function withStudents(int $count)
    {
        return $this->afterCreating(function (ProgramEdition $programEdition) {
            $programEdition->students->each(function (Student $student) use ($programEdition) {
                $student->fresh()->enroll($programEdition);
            });

            $programEdition->setRelation('enrollments', $programEdition->students->map(function (Student $student) {
                return new Enrollment([
                    'student_id' => $student->id,
                    'company_id' => $student->current_company_id,
                ]);
            }));
        })->afterMaking(function (ProgramEdition $programEdition) use ($count) {
            $students = Student::factory($count)->create([
                'current_company_id' => $programEdition->company_id,
            ]);

            $programEdition->setRelation('students', $students);
            $programEdition->setRelation('enrollments', $students->map(function (Student $student) {
                return new Enrollment([
                    'student_id' => $student->id,
                    'company_id' => $student->current_company_id,
                ]);
            }));
        });
    }

    public function withoutStudents()
    {
        return $this->state(fn (array $attributes) => []);
    }

    public function withEvaluations(int $count)
    {
        return $this->afterCreating(function (ProgramEdition $programEdition) use ($count) {
            $students = Student::factory($count)->create([
                'current_company_id' => $programEdition->company_id,
            ]);

            $students->each(function (Student $student) use ($programEdition) {
                $student->forceFill(['current_company_id' => $programEdition->company_id])->save();
                $student->fresh()->enroll($programEdition, [
                    'global_evaluation' => 'Eficaz',
                ]);
            });

            $programEdition->setRelation('students', $students);
            $programEdition->setRelation('enrollments', $students->map(function (Student $student) {
                return new Enrollment([
                    'student_id' => $student->id,
                    'company_id' => $student->current_company_id,
                    'global_evaluation' => 'Eficaz',
                ]);
            }));
        })->afterMaking(function (ProgramEdition $programEdition) use ($count) {
            $students = Student::factory($count)->make([
                'current_company_id' => $programEdition->company_id,
            ]);

            $programEdition->setRelation('students', $students);
            $programEdition->setRelation('enrollments', $students->map(function (Student $student) {
                return new Enrollment([
                    'student_id' => $student->id,
                    'company_id' => $student->current_company_id,
                    'global_evaluation' => 'Eficaz',
                ]);
            }));
        });
    }

    public function withoutEvaluations()
    {
        return $this->state(fn (array $attributes) => []);
    }
}
