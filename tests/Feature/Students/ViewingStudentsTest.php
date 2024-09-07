<?php

namespace Tests\Feature\Students;

use App\Http\Resources\StudentResource;
use App\Models\Company;
use App\Models\ProgramEdition;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class ViewingStudentsTest extends TestCase
{
    use RefreshDatabase;

    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->be(
            $this->user = User::factory()->create()
        );
    }

    /** @test */
    public function it_can_view_a_student()
    {
        $student = Student::factory()->create();

        $response = $this->actingAs($this->createAdminUser())->get("/students/{$student->id}");

        $response->assertJsonFragment($student->fresh()->toArray());
    }

    /** @test */
    public function a_user_without_rgpd_cannot_see_some_data()
    {
        $student = Student::factory()->create([
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
        $students = Student::factory()->times(4)->create();

        $response = $this->get('/students');

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
        $student = Student::factory()->create();

        $response = $this->get('/students');

        $viewDataStudents = $response->viewData('students');

        $this->assertEmpty(
            array_intersect_key(
                $viewDataStudents->first()->resource->toArray(),
                auth()->user()->can('rgpd') ? [] : (new Student)->rgpdFields
            )
        );
    }

    /** @test */
    public function it_shows_a_list_of_10_paginated_students()
    {
        Student::factory()->times(50)->create();

        $response = $this->get('/students');

        $response->assertViewHas('students');
        $this->assertCount(
            10,
            $response->viewData('students')->items()
        );
    }

    /** @test */
    public function it_can_filter_by_name()
    {
        $this->withoutExceptionHandling();

        $john = Student::factory()->create([
            'name' => 'John Test',
        ]);
        Student::factory()->create([
            'name' => 'Jane Test',
        ]);

        $response = $this->get('/students?filter[name]=John');

        $response->assertViewHas('students');
        $data = $response->viewData('students')->items();
        $this->assertTrue($john->fresh()->is($data[0]->resource));
    }

    /** @test */
    public function it_can_sort_students_by_company_name()
    {
        $this->withoutExceptionHandling();

        $first = Student::factory()->create([
            'current_company_id' => Company::factory()->create([
                'name' => 'ZCompany',
            ]),
        ]);
        $second = Student::factory()->create([
            'current_company_id' => Company::factory()->create([
                'name' => 'ACompany',
            ]),
        ]);

        $response = $this->get('/students?sort=company.name');

        $response->assertViewHas('students');
        $data = $response->viewData('students')->items();
        $this->assertTrue($first->fresh()->is($data[1]->resource));
        $this->assertTrue($second->fresh()->is($data[0]->resource));
    }

    /** @test */
    public function it_can_search_students_not_enrolled_in_program_edition()
    {
        $this->withoutExceptionHandling();

        $blankProgramEdition = ProgramEdition::factory()->create();
        $toEnrollProgramEdition = ProgramEdition::factory()->create();
        $notEnrolledStudent = Student::factory()->create([
            'name' => 'Jane Test',
        ]);
        $enrolledStudent = Student::factory()->create([
            'name' => 'John Test',
        ]);
        $enrolledStudent->enroll($toEnrollProgramEdition);

        $response = $this->getJson("/students?filter[not_enrolled]={$toEnrollProgramEdition->id}&sort=name");

        $response->assertJsonFragment([
            'id' => $notEnrolledStudent->fresh()->id,
            'name' => $notEnrolledStudent->name,
        ]);
        $response->assertJsonMissing([
            'name' => $enrolledStudent->name,
        ]);
    }

    /** @test */
    public function enrolled_students_get_enrolled_id_in_the_response()
    {
        $this->withoutExceptionHandling();

        $firstProgramEdition = ProgramEdition::factory()->create();
        $secondProgramEdition = ProgramEdition::factory()->create();
        $enrolledStudent = Student::factory()->create();
        $enrolledStudent->enroll($firstProgramEdition);
        $enrolledStudent->enroll($secondProgramEdition);

        $response = $this->getJson('/students?include=enrollments,enrolledProgramEditions');

        $firstStudentData = $response->json()['data'][0];
        $this->assertEquals(
            [
                $firstProgramEdition->id,
                $secondProgramEdition->id,
            ],
            collect($firstStudentData['enrollments'])->pluck('program_edition_id')->toArray()
        );
        $this->assertTrue($firstProgramEdition->fresh()->is(
            ProgramEdition::find($firstStudentData['enrolled_program_editions'][0]['id'])
        ));
        $this->assertTrue($secondProgramEdition->fresh()->is(
            ProgramEdition::find($firstStudentData['enrolled_program_editions'][1]['id'])
        ));
    }

    /** @test */
    public function it_default_sorts_by_name()
    {
        $this->withoutExceptionHandling();

        $john = Student::factory()->create([
            'name' => 'John Test',
        ]);
        $jane = Student::factory()->create([
            'name' => 'Jane Test',
        ]);

        $response = $this->get('/students');

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

        $first = Student::factory()->create();
        $second = Student::factory()->create();

        $response = $this->get('/students?sort=id');

        $response->assertViewHas('students');
        $data = $response->viewData('students')->items();
        $this->assertCount(2, $data);
        $this->assertTrue($first->fresh()->is($data[0]->resource));
        $this->assertTrue($second->fresh()->is($data[1]->resource));
    }
}
