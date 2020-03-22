<?php

namespace Tests\Feature\Companies;

use App\Company;
use App\User;
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

        $response = $this->get("/company/{$company->id}");

        $response->assertJson($company->fresh()->toArray());
    }

    /** @test */
    public function it_shows_a_list_of_companies()
    {
        $companies = factory(Company::class, 4)->create();

        $response = $this->get("/company");

        $response->assertViewHas('companies');
        $this->assertEquals(
            $companies->fresh()->all(),
            $response->viewData('companies')->items()
        );
    }

    /** @test */
    public function it_shows_a_list_of_20_paginated_companies()
    {
        factory(Company::class, 50)->create();

        $response = $this->get("/company");

        $response->assertViewHas('companies');
        $this->assertCount(
            20,
            $response->viewData('companies')->items()
        );
    }
}
