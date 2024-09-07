<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyYearlyBudget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company.$this->faker->randomNumber(2),
            'vat_number' => $this->faker->randomNumber(9, true),
        ];
    }

    public function withYearlyBudgets(int $count)
    {
        return $this->afterCreating(function (Company $company) use ($count) {
            CompanyYearlyBudget::factory($count)->create(['company_id' => $company->id]);
        })->afterMaking(function (Company $company) use ($count) {
            $company->setRelation('budgets', CompanyYearlyBudget::factory($count)->make(['company_id' => $company->id]));
        });
    }

    public function withoutYearlyBudgets()
    {
        return $this->state(fn (array $attributes) => []);
    }
}
