<?php

namespace Tests\Feature\Students;

use App\Student;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingStudentsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be(
            $this->user = $this->createAdminUser()
        );
    }

    /** @test */
    public function it_can_create_a_student()
    {
        $this->withoutExceptionHandling();

        $student = factory(Student::class)->make()->toArray();

        $response = $this->post('/students', $student);

        $response->assertCreated();
        $this->assertDatabaseHas('students', $student);
    }

    /** @test */
    public function a_name_is_required_for_a_student()
    {
        $student = factory(Student::class)->make([
            'name' => null,
        ])->toArray();

        $response = $this->post('/students', $student);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function an_email_is_required_for_a_student()
    {
        $student = factory(Student::class)->make([
            'email' => null,
        ])->toArray();

        $response = $this->post('/students', $student);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function when_storing_a_student_if_the_citizen_info_is_present_require_it()
    {
        $student = factory(Student::class)->state('with-citizen-information')->make([
            'citizen_id' => null,
        ])->toArray();
        $response = $this->post('/students', $student);
        $response->assertSessionHasErrors(['citizen_id']);

        $student = factory(Student::class)->state('with-citizen-information')->make([
            'citizen_id_validity' => null,
        ])->toArray();
        $response = $this->post('/students', $student);
        $response->assertSessionHasErrors(['citizen_id_validity']);

        $student = factory(Student::class)->state('without-citizen-information')->make()->toArray();
        $response = $this->post('/students', $student);
        $response->assertCreated();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function a_guest_cannot_create_a_student()
    {
        $student = factory(Student::class)->make()->toArray();

        $this->be(new User())->post('/students', $student);

        $this->assertDatabaseMissing('students', $student);
        $this->assertCount(0, student::all());
    }
}
