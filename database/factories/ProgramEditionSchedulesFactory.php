<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProgramEdition;
use App\ProgramEditionSchedules;
use Faker\Generator as Faker;

$factory->define(ProgramEditionSchedules::class, function (Faker $faker) {
    return [
        'program_edition_id' => function () {
            return factory(ProgramEdition::class)->create()->id;
        },
        'starts_at' => now(),
        'ends_at' => now()->addMinutes(120),
    ];
});
