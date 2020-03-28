<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\Program;
use App\ProgramEdition;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProgramEdition::class, function (Faker $faker) {
    return [
        'program_id' => factory(Program::class),
        'name' => 'Edition ' . mt_rand(1, 9999),
        'company_id' => factory(Company::class),
        'cost' => mt_rand(1, 9999),
        'supplier' => $faker->company,
        'teacher_name' => $faker->name,
        'starts_at' => today(),
        'ends_at' => today(),
        'created_by' => function () {
            return optional(auth()->user())->id ?: factory(User::class)->create()->id;
        },
    ];
});
