<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\Enrollment;
use App\ProgramEdition;
use App\Student;
use Faker\Generator as Faker;
use Illuminate\Support\Collection;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'postal_code' => $faker->postcode,
        'city' => $faker->city,
        'email' => $faker->safeEmail,
        'phone' => $faker->randomNumber(9),
        'birth_place' => $faker->city,
        'nationality' => 'PORTUGAL',
        'current_job_title' => $faker->jobTitle,
        'current_company_id' => factory(Company::class),
    ];
});

$factory->state(Student::class, 'with-citizen-information', function (Faker $faker) {
    return [
        'citizen_id' => $faker->randomNumber(8),
        'citizen_id_validity' => today()->addYears(10),
    ];
});
$factory->state(Student::class, 'without-citizen-information', []);

Collection::times(5)->each(function ($num) use ($factory) {
    $factory->state(Student::class, "with-{$num}-program-editions", [])
        ->afterCreatingState(Student::class, "with-{$num}-program-editions", function (Student $student) use ($num) {
            factory(ProgramEdition::class, $num)->create([
                'company_id' => $student->current_company_id,
            ])->each->enroll($student);
        })
        ->afterMakingState(Student::class, "with-{$num}-program-editions", function (Student $student) use ($num) {
            $student->setRelation('enrolledProgramEditions', $programEditions = factory(ProgramEdition::class, $num)->create([
                'company_id' => $student->current_company_id,
            ]));
            $student->setRelation('enrollments', $programEditions->map(function (ProgramEdition $programEdition) {
                return new Enrollment([
                    'program_edition_id' => $programEdition->id,
                    'company_id' => $programEdition->company_id,
                ]);
            }));
        });
});
$factory->state(Student::class, 'without-program-editions', []);
