<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProgramEdition;
use App\ProgramEditionSchedule;
use Faker\Generator as Faker;

$factory->define(ProgramEditionSchedule::class, function (Faker $faker) {
    return [
        'program_edition_id' => factory(ProgramEdition::class),
        'starts_at' => now(),
        'ends_at' => now()->addMinutes(120),
    ];
});
