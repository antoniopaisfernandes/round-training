<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\Program;
use App\ProgramEdition;
use App\ProgramEditionSchedule;
use App\User;
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
