<?php

namespace Tests\Feature\Students;

use App\Student;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewingStudentsTest extends TestCase
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
    public function it_can_view_a_student()
    {
        $student = factory(Student::class)->create();

        $response = $this->get("/student/{$student->id}");

        $response->assertJson([
            'student' => $student->fresh()->toArray(),
        ]);
    }

    /** @test */
    public function it_shows_a_list_of_students()
    {
        $students = factory(Student::class, 4)->create();

        $response = $this->get("/student");

        $response->assertViewHas('students');
        $this->assertEquals(
            $students->fresh()->all(),
            $response->viewData('students')->items()
        );
    }

    /** @test */
    public function it_shows_a_list_of_20_paginated_students()
    {
        factory(Student::class, 50)->create();

        $response = $this->get("/student");

        $response->assertViewHas('students');
        $this->assertCount(
            20,
            $response->viewData('students')->items()
        );
    }
}
