<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyYearlyBudget>
 */
class CompanyYearlyBudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'year' => $this->faker->unique()->numberBetween(1990, 2100),
            'budget' => $this->faker->numberBetween(0, 100000),
        ];
    }
}
