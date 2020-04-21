<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\CompanyYearlyBudget;
use Faker\Generator as Faker;

$factory->define(CompanyYearlyBudget::class, function (Faker $faker) {
    return [
        'company_id' => factory(Company::class),
        'year' => $faker->numberBetween(1990, 2100),
        'budget' => $faker->numberBetween(0, 100000),
    ];
});
