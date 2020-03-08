<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\Program;
use App\ProgramEdition;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProgramEdition::class, function (Faker $faker) {
    return [
        'program_id' => function () {
            return factory(Program::class)->create()->id;
        },
        'company_id' => function () {
            return factory(Company::class)->create()->id;
        },
        'supplier' => $faker->company,
        'teacher_name' => $faker->name,
        'starts_at' => today(),
        'ends_at' => today(),
        'created_by' => function () {
            return optional(auth()->user())->id ?: factory(User::class)->create()->id;
        },
    ];
});
