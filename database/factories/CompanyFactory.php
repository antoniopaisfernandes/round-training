<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use Faker\Generator as Faker;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company . $faker->randomNumber(2),
        'vat_number' => $faker->randomNumber(9, true),
    ];
});
