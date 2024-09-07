<?php

namespace Tests\Feature\ProgramEditions;

use App\Models\Enrollment;
use App\Models\ProgramEdition;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewingProgramEditionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_can_view_a_program_edition()
    {
        $this->withoutExceptionHandling();

        $programEdition = ProgramEdition::factory()->create();

        $response = $this->get("/program-editions/{$programEdition->id}");

        $response->assertOk()->assertJson($programEdition->fresh()->toArray());
    }

    /** @test */
    public function it_shows_a_list_of_programs()
    {
        $programEditions = ProgramEdition::factory()->times(4)->create();

        $response = $this->get("/program-editions");

        $response->assertViewHas('programEditions');
        $programEditionsFromView = collect($response->viewData('programEditions')->items())->map->resource;
        $this->assertContainsOnlyInstancesOf(ProgramEdition::class, $programEditionsFromView);
        $this->assertEquals($programEditions->count(), $programEditionsFromView->count());
    }

    /** @test */
    public function it_shows_a_list_of_10_paginated_programs()
    {
        ProgramEdition::factory()->times(50)->create();

        $response = $this->get("/program-editions");

        $response->assertViewHas('programEditions');
        $this->assertCount(
            10,
            $response->viewData('programEditions')->items()
        );
    }

    /** @test */
    public function the_list_is_ordered_using_start_date()
    {
        $first = ProgramEdition::factory()->create([
            'starts_at' => today(),
        ]);
        $second = ProgramEdition::factory()->create([
            'starts_at' => today()->addDay(),
        ]);

        $list = $this->get("/program-editions")->viewData('programEditions')->items();

        $this->assertEquals(
            [
                $second->id,
                $first->id,
            ],
            collect($list)->pluck('id')->toArray()
        );
    }

    /** @test */
    public function it_can_show_students_enrolled_in_program()
    {
        $programEditionEdition = ProgramEdition::factory()->create();
        $students = Student::factory()->times(9)->create();
        $programEditionEdition->enroll($students);
        $otherStudents = Student::factory()->times(11)->create();

        $response = $this->get("/program-editions/{$programEditionEdition->id}/students");

        $response->assertJson($students->toArray());
        $response->assertJsonMissing($otherStudents->toArray());
    }

    /** @test */
    public function an_instance_of_an_enrolled_student_is_an_enrollment()
    {
        $programEdition = ProgramEdition::factory()->create();
        $student = Student::factory()->create();
        $programEdition->enroll($student);

        $response = $this->get("/program-editions/{$programEdition->id}/students/{$student->id}");

        $response->assertJson(Enrollment::first()->toArray());
    }

    /** @test */
    public function a_program_edition_has_a_cost()
    {
        ProgramEdition::factory()->create([
            'cost' => 3000.04,
        ]);

        $this->assertDatabaseHas('program_editions', ['cost' => 3000.04]);
    }

    /** @test */
    public function a_user_without_rgpd_cannot_see_some_data_in_students_list()
    {
        $this->be(User::factory()->create());

        if (! $rgpdFields = auth()->user()->can('rgpd') ? [] : (new Student)->rgpdFields) {
            $this->markTestSkipped('There are no RGPD fields to filter');
        }

        $student = Student::factory()->create();
        $programEdition = ProgramEdition::factory()->create();
        $student->enroll($programEdition);

        $response = $this->get("/program-editions/{$programEdition->id}/students");

        foreach ($rgpdFields as $field) {
            $this->assertArrayNotHasKey($field, $response->json()[0]);
        }
    }
}
