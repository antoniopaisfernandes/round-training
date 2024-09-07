<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use App\Models\CompanyYearlyBudget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingCompaniesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_can_create_a_company()
    {
        $this->withoutExceptionHandling();

        $company = Company::factory()->make()->toArray();

        $response = $this->post('/companies', $company);

        $response->assertOk();
        $this->assertDatabaseHas('companies', $company);
    }

    /** @test */
    public function a_guest_cannot_create_a_company()
    {
        $company = Company::factory()->make()->toArray();

        $this->be(new User())->post('/companies', $company);

        $this->assertDatabaseMissing('companies', $company);
        $this->assertCount(0, Company::all());
    }

    /** @test */
    public function a_name_is_required_for_a_company()
    {
        $company = Company::factory()->make([
            'name' => null,
        ])->toArray();

        $response = $this->post('/companies', $company);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function a_vat_number_is_required_for_a_company()
    {
        $company = Company::factory()->make([
            'vat_number' => null,
        ])->toArray();

        $response = $this->post('/companies', $company);

        $response->assertSessionHasErrors(['vat_number']);
    }

    /** @test */
    public function a_company_can_have_a_short_name()
    {
        $company = Company::factory()->make([
            'short_name' => 'SHORT',
        ])->toArray();

        $this->post('/companies', $company)->assertOk();

        $this->assertDatabaseHas('companies', $company);
    }

    /** @test */
    public function a_company_can_have_a_coordinator()
    {
        $this->withoutExceptionHandling();

        $company = Company::factory()->make([
            'coordinator_id' => User::factory(),
        ])->toArray();

        $this->post('/companies', $company)->assertOk();

        $this->assertDatabaseHas('companies', $company);
    }

    /** @test */
    public function when_creating_a_company_it_can_add_yearly_budgets()
    {
        $this->withoutExceptionHandling();

        $company = Company::factory()->withYearlyBudgets(2)->make()->toArray();

        $response = $this->post('/companies', $company);

        $response->assertOk();
        $this->assertCount(2, CompanyYearlyBudget::all());
    }

    /** @test */
    public function the_yearly_budgets_cannot_refer_to_the_same_year()
    {
        $company = Company::factory()->withYearlyBudgets(2)->make()->toArray();
        $company['budgets'][0]['year'] = 2020;
        $company['budgets'][1]['year'] = 2020;

        $response = $this->post('/companies', $company)->assertRedirect();

        $response->assertSessionHasErrors(['budgets.0.year']);
    }
}
