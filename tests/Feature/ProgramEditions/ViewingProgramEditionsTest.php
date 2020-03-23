<?php

namespace Tests\Feature\ProgramEditions;

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

        $this->be(
            $this->user = factory(User::class)->create()
        );
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
        $programEditionsFromView = collect($response->viewData('programEditions')->items());
        $this->assertContainsOnlyInstancesOf(ProgramEdition::class, $programEditionsFromView);
        $this->assertEquals($programEditions->count(), $programEditionsFromView->count());
    }

    /** @test */
    public function it_shows_a_list_of_20_paginated_programs()
    {
        factory(ProgramEdition::class, 50)->create();

        $response = $this->get("/program-editions");

        $response->assertViewHas('programEditions');
        $this->assertCount(
            20,
            $response->viewData('programEditions')->items()
        );
    }

    /** @test */
    public function the_list_is_ordered_using_start_date()
    {
        $this->withoutExceptionHandling();

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
        $this->withoutExceptionHandling();

        $programEditionEdition = factory(ProgramEdition::class)->create();

        $students = factory(Student::class, 9)->create();
        $programEditionEdition->enroll($students);

        $otherStudents = factory(Student::class, 11)->create();

        $response = $this->get("/program-editions/{$programEditionEdition->id}/students");

        $response->assertJson($students->toArray());
        $response->assertJsonMissing($otherStudents->toArray());
    }
}
