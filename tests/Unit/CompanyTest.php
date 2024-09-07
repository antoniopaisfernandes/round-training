<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\ProgramEdition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_correctly_calculates_the_yearly_costs_of_the_programs()
    {
        $programEdition = ProgramEdition::factory()->create([
            'cost' => 105.33,
            'starts_at' => '2020-01-01',
        ]);

        $this->assertEquals(
            105.33,
            $programEdition->company->executedCostsInYear(2020)
        );
    }

    /** @test */
    public function it_correctly_calculates_the_yearly_costs_of_the_programs_with_multiple_editions()
    {
        $company = Company::factory()->create();
        ProgramEdition::factory()->create([
            'cost' => 105.33,
            'starts_at' => '2020-01-01',
            'company_id' => $company->id,
        ]);
        ProgramEdition::factory()->create([
            'cost' => 94.43,
            'starts_at' => '2020-12-01',
            'company_id' => $company->id,
        ]);
        ProgramEdition::factory()->create([
            'cost' => 105.33,
            'starts_at' => '2020-02-01',
            'company_id' => Company::factory(),
        ]);

        $this->assertEquals(
            105.33 + 94.43,
            $company->executedCostsInYear(2020)
        );
    }

    /** @test */
    public function it_has_a_coordinator()
    {
        $company = Company::factory()->create([
            'coordinator_id' => $user_id = User::factory()->create()->id,
        ])->fresh();

        $this->assertTrue(User::find($user_id)->is($company->coordinator));
    }
}
