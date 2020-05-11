<?php

namespace Tests\Feature\ProgramEditions;

use App\Enrollment;
use App\ProgramEdition;
use App\Student;
use App\User;
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

        $programEdition = factory(ProgramEdition::class)->create();

        $response = $this->get("/program-editions/{$programEdition->id}");

        $response->assertOk()->assertJson($programEdition->fresh()->toArray());
    }

    /** @test */
    public function it_shows_a_list_of_programs()
    {
        $programEditions = factory(ProgramEdition::class, 4)->create();

        $response = $this->get("/program-editions");

        $response->assertViewHas('programEditions');
        $programEditionsFromView = collect($response->viewData('programEditions')->items())->map->resource;
        $this->assertContainsOnlyInstancesOf(ProgramEdition::class, $programEditionsFromView);
        $this->assertEquals($programEditions->count(), $programEditionsFromView->count());
    }

    /** @test */
    public function it_shows_a_list_of_10_paginated_programs()
    {
        factory(ProgramEdition::class, 50)->create();

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
        $first = factory(ProgramEdition::class)->create([
            'starts_at' => today(),
        ]);
        $second = factory(ProgramEdition::class)->create([
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
        $programEditionEdition = factory(ProgramEdition::class)->create();
        $students = factory(Student::class, 9)->create();
        $programEditionEdition->enroll($students);
        $otherStudents = factory(Student::class, 11)->create();

        $response = $this->get("/program-editions/{$programEditionEdition->id}/students");

        $response->assertJson($students->toArray());
        $response->assertJsonMissing($otherStudents->toArray());
    }

    /** @test */
    public function an_instance_of_an_enrolled_student_is_an_enrollment()
    {
        $programEdition = factory(ProgramEdition::class)->create();
        $student = factory(Student::class)->create();
        $programEdition->enroll($student);

        $response = $this->get("/program-editions/{$programEdition->id}/students/{$student->id}");

        $response->assertJson(Enrollment::first()->toArray());
    }

    /** @test */
    public function a_program_edition_has_a_cost()
    {
        factory(ProgramEdition::class)->create([
            'cost' => 3000.04,
        ]);

        $this->assertDatabaseHas('program_editions', ['cost' => 3000.04]);
    }

    /** @test */
    public function a_user_without_rgpd_cannot_see_some_data_in_students_list()
    {
        $this->be(factory(User::class)->create());

        if (! $rgpdFields = auth()->user()->can('rgpd') ? [] : (new Student)->rgpdFields) {
            $this->markTestSkipped('There are no RGPD fields to filter');
        }

        $student = factory(Student::class)->create();
        $programEdition = factory(ProgramEdition::class)->create();
        $student->enroll($programEdition);

        $response = $this->get("/program-editions/{$programEdition->id}/students");

        foreach ($rgpdFields as $field) {
            $this->assertArrayNotHasKey($field, $response->json()[0]);
        }
    }
}
