<?php

namespace Tests\Feature\Students;

use App\Http\Resources\StudentResource;
use App\ProgramEdition;
use App\Student;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ViewingStudentsTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $user;

    public function setUp() : void
    {
        parent::setUp();

        $this->be(
            $this->user = factory(User::class)->create()
        );
    }

    /** @test */
    public function it_can_view_a_student()
    {
        $student = factory(Student::class)->create();

        $response = $this->actingAs($this->createAdminUser())->get("/students/{$student->id}");

        $response->assertJsonFragment($student->fresh()->toArray());
    }

    /** @test */
    public function a_user_without_rgpd_cannot_see_some_data()
    {
        $student = factory(Student::class)->create([
            'citizen_id' => '123456789',
            'citizen_id_validity' => today()->addYear()->format('Y-m-d'),
        ]);

        $response = $this->get("/students/{$student->id}");

        $response->assertJsonFragment(Arr::except($student->toArray(), ['citizen_id', 'citizen_id_validity']));
        $response->assertJsonMissing([
            'citizen_id' => $student->citizen_id,
            'citizen_id_validity' => $student->citizen_id_validity,
        ]);
    }

    /** @test */
    public function it_shows_a_list_of_students()
    {
        $students = factory(Student::class, 4)->create();

        $response = $this->get("/students");

        $response->assertViewHas('students');
        $viewDataStudents = $response->viewData('students');
        $this->assertInstanceOf(StudentResource::class, $viewDataStudents->first());
        $this->assertEquals(
            StudentResource::collection($students->fresh())->resolve(),
            collect($viewDataStudents->resolve())->sortBy('id')->values()->all()
        );
    }

    /** @test */
    public function a_user_without_rgpd_cannot_see_some_data_in_students_list()
    {
        $student = factory(Student::class)->create();

        $response = $this->get("/students");

        $viewDataStudents = $response->viewData('students');

        $this->assertEmpty(
            array_intersect_key(
                $viewDataStudents->first()->resource->toArray(),
                auth()->user()->can('rgpd') ? [] : (new Student)->rgpdFields
            )
        );
    }

    /** @test */
    public function it_shows_a_list_of_20_paginated_students()
    {
        factory(Student::class, 50)->create();

        $response = $this->get("/students");

        $response->assertViewHas('students');
        $this->assertCount(
            20,
            $response->viewData('students')->items()
        );
    }

    /** @test */
    public function it_can_filter_by_name()
    {
        $this->withoutExceptionHandling();

        $john = factory(Student::class)->create([
            'name' => 'John Test'
        ]);
        factory(Student::class)->create([
            'name' => 'Jane Test'
        ]);

        $response = $this->get("/students?filter[name]=John");

        $response->assertViewHas('students');
        $data = $response->viewData('students')->items();
        $this->assertTrue($john->fresh()->is($data[0]->resource));
    }

    /** @test */
    public function it_can_search_students_not_enrolled_in_program_edition()
    {
        $this->withoutExceptionHandling();

        $blankProgramEdition = factory(ProgramEdition::class)->create();
        $toEnrollProgramEdition = factory(ProgramEdition::class)->create();
        $notEnrolledStudent = factory(Student::class)->create([
            'name' => 'Jane Test'
        ]);
        $enrolledStudent = factory(Student::class)->create([
            'name' => 'John Test'
        ]);
        $enrolledStudent->enroll($toEnrollProgramEdition);

        $response = $this->getJson("/students?filter[not_enrolled]={$toEnrollProgramEdition->id}&sort=name");

        $response->assertJsonFragment([
            'id' => $notEnrolledStudent->fresh()->id,
            'name' => $notEnrolledStudent->name,
        ]);
        $response->assertJsonMissing([
            'id' => $enrolledStudent->fresh()->id,
            'name' => $enrolledStudent->name,
        ]);
    }

    /** @test */
    public function it_default_sorts_by_name()
    {
        $this->withoutExceptionHandling();

        $john = factory(Student::class)->create([
            'name' => 'John Test'
        ]);
        $jane = factory(Student::class)->create([
            'name' => 'Jane Test'
        ]);

        $response = $this->get("/students");

        $response->assertViewHas('students');
        $data = $response->viewData('students')->items();
        $this->assertCount(2, $data);
        $this->assertTrue($jane->fresh()->is($data[0]->resource));
        $this->assertTrue($john->fresh()->is($data[1]->resource));
    }

    /** @test */
    public function it_can_sort_by_id()
    {
        $this->withoutExceptionHandling();

        $first = factory(Student::class)->create();
        $second = factory(Student::class)->create();

        $response = $this->get("/students?sort=id");

        $response->assertViewHas('students');
        $data = $response->viewData('students')->items();
        $this->assertCount(2, $data);
        $this->assertTrue($first->fresh()->is($data[0]->resource));
        $this->assertTrue($second->fresh()->is($data[1]->resource));
    }
}
