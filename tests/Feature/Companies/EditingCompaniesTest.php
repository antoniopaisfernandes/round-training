<?php

namespace Tests\Feature\Companies;

use App\Company;
use App\CompanyYearlyBudget;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditingCompaniesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be(
            $this->user = $this->createAdminUser()
        );
    }

    /** @test */
    public function it_can_edit_a_company()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create([
            'name' => 'Old name',
            'vat_number' => '123456789',
        ]);

        $this->patch("/companies/{$company->id}", [
            'name' => 'New name',
            'vat_number' => '987654321',
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'New name',
            'vat_number' => '987654321',
        ]);
    }

    /** @test */
    public function a_guest_cannot_updating_a_company()
    {
        $company = factory(Company::class)->create([
            'name' => 'Old name',
        ]);

        $this->be(new User())->patch("/companies/{$company->id}", [
            'name' => null,
        ])->assertStatus(403);

        $this->assertDatabaseHas('companies', [
            'name' => 'Old name',
        ]);
    }

    /** @test */
    public function a_name_is_required_updating_a_company()
    {
        $company = factory(Company::class)->create([
            'name' => 'Old name',
        ]);

        $response = $this->patch("/companies/{$company->id}", [
            'name' => null,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('companies', [
            'name' => 'Old name',
        ]);
    }

    /** @test */
    public function a_vat_number_is_required_updating_a_company()
    {
        $company = factory(Company::class)->create([
            'vat_number' => 'Old',
        ]);

        $response = $this->patch("/companies/{$company->id}", [
            'vat_number' => null,
        ]);

        $response->assertSessionHasErrors(['vat_number']);
        $this->assertDatabaseHas('companies', [
            'vat_number' => 'Old',
        ]);
    }

    /** @test */
    public function the_short_name_can_be_edited()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create([
            'short_name' => 'OLD',
        ]);

        $this->patch("/companies/{$company->id}", array_merge(
            $company->toArray(),
            [
                'short_name' => 'NEW',
            ]
        ));

        $this->assertDatabaseHas('companies', [
            'short_name' => 'NEW',
        ]);
    }

    /** @test */
    public function when_editing_a_company_it_can_add_yearly_budgets()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create();

        $response = $this->patch("/companies/{$company->id}", array_merge(
            $company->toArray(),
            [
                'budgets' => factory(CompanyYearlyBudget::class, 2)->make()->toArray(),
            ]
        ));

        $response->assertOk();
        $this->assertCount(2, CompanyYearlyBudget::all());
    }

    /** @test */
    public function when_editing_a_company_it_can_change_yearly_budgets()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->state('with-2-yearly-budgets')->create();

        $response = $this->patch("/companies/{$company->id}", array_merge(
            $company->toArray(),
            [
                'budgets' => factory(CompanyYearlyBudget::class, 4)->make()->toArray(),
            ]
        ));

        $response->assertOk();
        $this->assertCount(4, CompanyYearlyBudget::all());
    }
}
