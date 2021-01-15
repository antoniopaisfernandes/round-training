<?php

namespace Tests\Feature\Other;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_home_dashboard()
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs($this->createAdminUser())->get('/');

        $response->assertOk();
        $response->assertSee('Cursos terminados');
        $response->assertSee('Cursos a decorrer');
        $response->assertSee('Cursos futuros');
    }
}
