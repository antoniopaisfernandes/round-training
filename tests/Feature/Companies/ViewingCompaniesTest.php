<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewingCompaniesTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    private $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->user = User::factory()->create());
    }

    /** @test */
    public function it_can_view_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->get("/companies/{$company->id}");

        $response->assertJson($company->fresh()->toArray());
    }

    /** @test */
    public function it_shows_a_list_of_companies()
    {
        $companies = Company::factory()->times(4)->create();

        $response = $this->get("/companies");

        $response->assertViewHas('companies');
        $this->assertEquals(
            $companies->map(function ($company) {
                return $company->with(['budgets','coordinator'])->find($company->id);
            })->sortBy('id')->values()->toArray(),
            collect($response->viewData('companies'))->sortBy('id')->values()->toArray()
        );
    }

    /** @test */
    public function the_companies_index_method_does_not_paginate()
    {
        Company::factory()->times(51)->create();

        $response = $this->get("/companies");

        $response->assertViewHas('companies');
        $this->assertCount(
            51,
            $response->viewData('companies')
        );
    }
}
