<?php

namespace Tests\Feature\Students;

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
        $this->assertEquals(
            $students->fresh()->all(),
            $response->viewData('students')->items()
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
}
