<?php

namespace Tests\Feature\Reports;

use App\Models\ProgramEdition;
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

        $response->assertOk()
                ->assertSee(__('app.reports'));
    }

    /** @test */
    public function it_can_generate_a_execution_report()
    {
        $this->withoutExceptionHandling();

        $this->actingAs($this->createAdminUser())
            ->post(
                route('reports.show', ['report' => 'execution']),
                [
                    'begin_date' => null,
                    'end_date' => null,
                    'name' => 'execution',
                    'year' => date('Y'),
                ]
            )
            ->assertOk();
    }

    /** @test */
    public function it_can_generate_a_evaluation_report()
    {
        $this->withoutExceptionHandling();

        $programEdition = factory(ProgramEdition::class)->create();

        $this->actingAs($this->createAdminUser())
            ->post(
                route('reports.show', ['report' => 'evaluation']),
                [
                    'name' => 'evaluation',
                    'program_edition_id' => $programEdition->id,
                ]
            )
            ->assertOk();
    }
}
