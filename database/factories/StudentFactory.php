<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'address' => $faker->address,
        'postal_code' => $faker->postcode,
        'city' => $faker->city,
        'citizen_id' => $faker->randomNumber(8),
        'email' => $faker->safeEmail,
        'phone' => $faker->randomNumber(9),
        'birth_place' => $faker->city,
        'nationality' => 'PORTUGAL',
        'current_job_title' => $faker->jobTitle,
    ];
});
