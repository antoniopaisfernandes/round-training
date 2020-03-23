<?php

namespace Tests\Feature\Companies;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingCompaniesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_company()
    {
        $company = factory(Company::class)->create();

        $this->assertCount(1, Company::all());

        $this->actingAs($this->createAdminUser())->delete("/companies/{$company->id}");

        $this->assertCount(0, Company::all());
    }

    /** @test */
    public function it_requires_necessary_permissions_to_delete_a_company()
    {
        $userWithoutPermission = factory(User::class)->create();
        $company = factory(Company::class)->create();

        $this->assertCount(1, Company::all());

        $this->actingAs($userWithoutPermission)->delete("/companies/{$company->id}")->assertStatus(403);

        $this->assertCount(1, Company::all());
    }
}
