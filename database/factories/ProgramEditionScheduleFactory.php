<?php

namespace Database\Factories;

use App\Models\ProgramEdition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramEditionSchedule>
 */
class ProgramEditionScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_edition_id' => ProgramEdition::factory(),
            'starts_at' => today()->hour(9)->minute(0)->second(0),
            'ends_at' => today()->addDay()->hour(18)->minute(30)->second(0),
        ];
    }
}
