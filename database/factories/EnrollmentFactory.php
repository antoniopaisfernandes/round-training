<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\Enrollment;
use App\Models\ProgramEdition;
use App\Models\Student;
use Faker\Generator as Faker;

$factory->define(Enrollment::class, function (Faker $faker) {
    $company = factory(Company::class)->create();

    return [
        'program_edition_id' => function () use ($company) {
            return factory(ProgramEdition::class)->create([
                'company_id' => $company->id,
            ])->id;
        },
        'student_id' => factory(Student::class),
        'company_id' => function () use ($company) {
            return $company->id;
        },
    ];
});
