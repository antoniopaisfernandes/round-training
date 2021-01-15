<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Company;
use App\Models\CompanyYearlyBudget;
use Faker\Generator as Faker;
use Illuminate\Support\Collection;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company . $faker->randomNumber(2),
        'vat_number' => $faker->randomNumber(9, true),
    ];
});

Collection::times(5)->each(function ($num) use ($factory) {
    $factory->state(Company::class, "with-{$num}-yearly-budgets", [])
        ->afterCreatingState(Company::class, "with-{$num}-yearly-budgets", function (Company $company) use ($num) {
            factory(CompanyYearlyBudget::class, $num)->create([
                'company_id' => $company->id,
            ]);
        })
        ->afterMakingState(Company::class, "with-{$num}-yearly-budgets", function (Company $company) use ($num) {
            $company->setRelation('budgets', factory(CompanyYearlyBudget::class, $num)->make([
                'company_id' => $company->id,
            ]));
        });
});
$factory->state(Company::class, 'without-yearly-budgets', []);
