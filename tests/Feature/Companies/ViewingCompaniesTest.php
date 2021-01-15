<?php

namespace Tests\Feature\Companies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewingCompaniesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be(
            $this->user = factory(User::class)->create()
        );
    }

    /** @test */
    public function it_can_view_a_company()
    {
        $company = factory(Company::class)->create();

        $response = $this->get("/companies/{$company->id}");

        $response->assertJson($company->fresh()->toArray());
    }

    /** @test */
    public function it_shows_a_list_of_companies()
    {
        $companies = factory(Company::class, 4)->create();

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
        factory(Company::class, 51)->create();

        $response = $this->get("/companies");

        $response->assertViewHas('companies');
        $this->assertCount(
            51,
            $response->viewData('companies')
        );
    }
}
