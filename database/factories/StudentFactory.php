<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\Student;
use Faker\Generator as Faker;

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
