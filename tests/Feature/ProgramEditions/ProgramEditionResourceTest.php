<?php

namespace Tests\Feature\ProgramEditions;

use App\Http\Resources\ProgramEditionResource;
use App\Models\ProgramEdition;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProgramEditionResourceTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    /** @test */
    public function when_getting_a_program_edition_resource_with_students_hide_rgpd_data()
    {
        $programEditionId = ProgramEdition::factory()->create()->enroll(
            Student::factory()->create([
                'citizen_id' => '11111111',
                'citizen_id_validity' => '2030-12-31',
            ])
        )->id;
        $programEdition = ProgramEdition::with('students')->findOrFail($programEditionId);

        $resource = ProgramEditionResource::make($programEdition);
        $studentFromResource = $resource->response()->getData(true)['students'][0];

        $this->assertFalse(isset($studentFromResource['citizen_id']));
        $this->assertFalse(isset($studentFromResource['citizen_id_validity']));
        $this->assertTrue(isset($studentFromResource['name']));
    }

    /** @test */
    public function when_getting_a_program_edition_resource_with_students_show_rgpd_data()
    {
        $programEditionId = ProgramEdition::factory()->create()->enroll(
            Student::factory()->create([
                'citizen_id' => '11111111',
                'citizen_id_validity' => '2030-12-31',
            ])
        )->id;
        $programEdition = ProgramEdition::with('students')->findOrFail($programEditionId);

        $request = request()->setUserResolver(fn () => $this->createAdminUser());
        $studentFromResource = ProgramEditionResource::make($programEdition)->toArray($request)['students'][0];

        $this->assertEquals('11111111', $studentFromResource['citizen_id']);
        $this->assertEquals('2030-12-31', $studentFromResource['citizen_id_validity']);
    }

    /** @test */
    public function it_can_get_program_edition_without_starts_at()
    {
        $programEdition = ProgramEdition::factory()->create([
            'starts_at' => null,
        ]);

        $resource = ProgramEditionResource::make($programEdition);
        $programEditionFromResource = $resource->response()->getData(true);

        $this->assertNull($programEditionFromResource['starts_at']);
    }

    /** @test */
    public function it_can_get_program_edition_without_ends_at()
    {
        $programEdition = ProgramEdition::factory()->create([
            'ends_at' => null,
        ]);

        $resource = ProgramEditionResource::make($programEdition);
        $programEditionFromResource = $resource->response()->getData(true);

        $this->assertNull($programEditionFromResource['ends_at']);
    }
}
