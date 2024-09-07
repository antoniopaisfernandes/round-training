<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use App\Models\CompanyYearlyBudget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditingCompaniesTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->user = $this->createAdminUser());
    }

    /** @test */
    public function it_can_edit_a_company()
    {
        $this->withoutExceptionHandling();

        $company = Company::factory()->create([
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
        $company = Company::factory()->create([
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
        $company = Company::factory()->create([
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
        $company = Company::factory()->create([
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

        $company = Company::factory()->create([
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
    public function the_coordinator_can_be_changed()
    {
        $this->withoutExceptionHandling();

        $company = Company::factory()->create([
            'coordinator_id' => User::factory(),
        ]);

        $this->patch("/companies/{$company->id}", array_merge(
            $company->toArray(),
            [
                'coordinator_id' => $user_id = User::factory()->create()->id,
            ]
        ));

        $this->assertDatabaseHas('companies', [
            'coordinator_id' => $user_id,
        ]);
    }

    /** @test */
    public function when_editing_a_company_it_can_add_yearly_budgets()
    {
        $this->withoutExceptionHandling();

        $company = Company::factory()->create();

        $response = $this->patch("/companies/{$company->id}", array_merge(
            $company->toArray(),
            [
                'budgets' => CompanyYearlyBudget::factory()->times(2)->make()->toArray(),
            ]
        ));

        $response->assertOk();
        $this->assertCount(2, CompanyYearlyBudget::all());
    }

    /** @test */
    public function when_editing_a_company_it_can_change_yearly_budgets()
    {
        $this->withoutExceptionHandling();

        $company = Company::factory()->withYearlyBudgets(2)->create();

        $response = $this->patch("/companies/{$company->id}", array_merge(
            $company->toArray(),
            [
                'budgets' => CompanyYearlyBudget::factory()->times(4)->make()->toArray(),
            ]
        ));

        $response->assertOk();
        $this->assertCount(4, CompanyYearlyBudget::all());
    }
}
