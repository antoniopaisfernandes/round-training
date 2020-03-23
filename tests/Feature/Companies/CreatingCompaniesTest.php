<?php

namespace Tests\Feature\Companies;

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingCompaniesTest extends TestCase
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
    public function it_can_create_a_company()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->make()->toArray();

        $response = $this->post('/company', $company);

        $response->assertOk();
        $this->assertDatabaseHas('companies', $company);
    }

    /** @test */
    public function a_name_is_required_for_a_company()
    {
        $company = factory(Company::class)->make([
            'name' => null,
        ])->toArray();

        $response = $this->post('/company', $company);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function a_vat_number_is_required_for_a_company()
    {
        $company = factory(Company::class)->make([
            'vat_number' => null,
        ])->toArray();

        $response = $this->post('/company', $company);

        $response->assertSessionHasErrors(['vat_number']);
    }

    /** @test */
    public function a_guest_cannot_create_a_company()
    {
        $company = factory(Company::class)->make()->toArray();

        $this->be(new User())->post('/company', $company);

        $this->assertDatabaseMissing('companies', $company);
        $this->assertCount(0, Company::all());
    }
}