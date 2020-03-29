<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProgramEdition;
use App\ProgramEditionSchedule;
use Faker\Generator as Faker;

$factory->define(ProgramEditionSchedule::class, function (Faker $faker) {
    return [
        'program_edition_id' => factory(ProgramEdition::class),
        'starts_at' => today()->hour(9)->minute(0)->second(0),
        'ends_at' => today()->addDay()->hour(18)->minute(30)->second(0),
    ];
});
