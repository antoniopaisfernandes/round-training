<?php

namespace Tests\Feature\Companies;

use App\Company;
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
    public function a_guest_cannot_updating_a_company()
    {
        $company = factory(Company::class)->create([
            'name' => 'Old name',
        ]);

        $this->be(new User())->patch("/companies/{$company->id}", [
            'name' => null,
        ]);

        $this->assertDatabaseHas('companies', [
            'name' => 'Old name',
        ]);
    }
}
