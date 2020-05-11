<?php

namespace Tests\Feature\ProgramEditions;

use App\Http\Resources\ProgramEditionResource;
use App\ProgramEdition;
use App\Student;
use App\User;
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
        $this->markTestSkipped('Todo');

        $programEdition = factory(ProgramEdition::class)
                            ->state('with-1-students')
                            ->create()
                            ->fresh(['students']);
        $programEdition->students->each(function(Student $student) {
            $student->fill([
                'citizen_id' => '11111111',
                'citizen_id_validity' => '2030-12-31',
            ])
            ->saveOrFail();
        });
        $programEdition = ProgramEdition::with('students')->findOrFail($programEdition->id);

        $request = request()->setUserResolver(fn () => new User);
        $studentFromResource = ProgramEditionResource::make($programEdition)->toArray($request)['students'][0];
        $this->assertFalse(isset($studentFromResource['citizen_id']));
        $this->assertFalse(isset($studentFromResource['citizen_id_validity']));
    }

    /** @test */
    public function when_getting_a_program_edition_resource_with_students_show_rgpd_data()
    {
        $programEdition = factory(ProgramEdition::class)
                            ->state('with-1-students')
                            ->create()
                            ->fresh(['students']);
        $programEdition->students->each(function(Student $student) {
            $student->fill([
                'citizen_id' => '11111111',
                'citizen_id_validity' => '2030-12-31',
            ])
            ->saveOrFail();
        });
        $programEdition = ProgramEdition::with('students')->findOrFail($programEdition->id);

        $request = request()->setUserResolver(fn () => $this->createAdminUser());
        $studentFromResource = ProgramEditionResource::make($programEdition)->toArray($request)['students'][0];
        $this->assertEquals('11111111', $studentFromResource['citizen_id']);
        $this->assertEquals('2030-12-31', $studentFromResource['citizen_id_validity']);
    }
}
