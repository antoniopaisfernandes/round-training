<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\ProgramEdition;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = Company::factory()->create();

        return [
            'program_edition_id' => fn () => ProgramEdition::factory()->create(['company_id' => $company->id])->id,
            'student_id' => Student::factory(),
            'company_id' => $company->id,
        ];
    }
}
