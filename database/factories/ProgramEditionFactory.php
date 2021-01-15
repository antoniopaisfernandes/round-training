<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\Enrollment;
use App\Models\Program;
use App\Models\ProgramEdition;
use App\Models\ProgramEditionSchedule;
use App\Models\Student;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Collection;

$factory->define(ProgramEdition::class, function (Faker $faker) {
    return [
        'program_id' => factory(Program::class),
        'name' => 'Edition ' . mt_rand(1, 9999),
        'company_id' => factory(Company::class),
        'cost' => mt_rand(1, 9999),
        'supplier' => $faker->company,
        'teacher_name' => $faker->name,
        'starts_at' => today(),
        'ends_at' => today()->addDay(),
        'created_by' => function () {
            return optional(auth()->user())->id ?: factory(User::class)->create()->id;
        },
    ];
});

// Make 5 different states for schedules
Collection::times(5)->each(function ($num) use ($factory) {
    $factory->state(ProgramEdition::class, "with-{$num}-schedules", [])
            ->afterCreatingState(ProgramEdition::class, "with-{$num}-schedules", function (ProgramEdition $programEdition) use ($num) {
                factory(ProgramEditionSchedule::class, $num)->create([
                    'program_edition_id' => $programEdition->id,
                ]);
            })
            ->afterMakingState(ProgramEdition::class, "with-{$num}-schedules", function (ProgramEdition $programEdition) use ($num) {
                $programEdition->setRelation('schedules', factory(ProgramEditionSchedule::class, $num)->make([
                    'program_edition_id' => $programEdition->id,
                ]));
            });
});
$factory->state(ProgramEdition::class, 'without-schedules', []);

// Make 5 different states for student enrollments
Collection::times(5)->each(function ($num) use ($factory) {
    $factory->state(ProgramEdition::class, "with-{$num}-students", [])
        ->afterCreatingState(ProgramEdition::class, "with-{$num}-students", function (ProgramEdition $programEdition) use ($num) {
            $programEdition->students
                ->each(fn ($student) => $student->forceFill(['current_company_id' => $programEdition->company_id])->save())
                ->each
                ->fresh()
                ->each
                ->enroll($programEdition);
        })
        ->afterMakingState(ProgramEdition::class, "with-{$num}-students", function (ProgramEdition $programEdition) use ($num) {
            $programEdition->setRelation('students', $students = factory(Student::class, $num)->create([
                'current_company_id' => $programEdition->company_id,
            ]));
            $programEdition->setRelation('enrollments', $students->map(function (Student $student) {
                return new Enrollment([
                    'student_id' => $student->id,
                    'company_id' => $student->current_company_id,
                ]);
            }));
        });
});
$factory->state(ProgramEdition::class, 'without-students', []);

// Make 5 different states for student evaluations
Collection::times(5)->each(function ($num) use ($factory) {
    $factory->state(ProgramEdition::class, "with-{$num}-evaluations", [])
        ->afterCreatingState(ProgramEdition::class, "with-{$num}-evaluations", function (ProgramEdition $programEdition) use ($num) {
            $programEdition->students
                ->each(fn ($student) => $student->forceFill(['current_company_id' => $programEdition->company_id])->save())
                ->each
                ->fresh()
                ->each
                ->enroll($programEdition, [
                    'global_evaluation' => 'Eficaz',
                ]);
        })
        ->afterMakingState(ProgramEdition::class, "with-{$num}-evaluations", function (ProgramEdition $programEdition) use ($num) {
            $programEdition->setRelation('students', $students = factory(Student::class, $num)->create([
                'current_company_id' => $programEdition->company_id,
            ]));
            $programEdition->setRelation('enrollments', $students->map(function (Student $student) {
                return new Enrollment([
                    'student_id' => $student->id,
                    'company_id' => $student->current_company_id,
                    'global_evaluation' => 'Eficaz',
                ]);
            }));
        });
});
$factory->state(ProgramEdition::class, 'without-evaluations', []);
