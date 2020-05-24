<?php

namespace Tests\Feature\Other;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_reports_dashboard()
    {
        $response = $this->actingAs($this->createAdminUser())->get('/reports');

        $response->assertOk();
        $response->assertSee('Relatórios');
    }
}
