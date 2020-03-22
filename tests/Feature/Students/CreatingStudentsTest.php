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

        $response = $this->post('/student', $student);

        $response->assertOk();
        $this->assertDatabaseHas('students', $student);
    }

    /** @test */
    public function a_name_is_required_for_a_student()
    {
        $student = factory(Student::class)->make([
            'name' => null,
        ])->toArray();

        $response = $this->post('/student', $student);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function an_email_is_required_for_a_student()
    {
        $student = factory(Student::class)->make([
            'email' => null,
        ])->toArray();

        $response = $this->post('/student', $student);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function when_storing_a_student_if_the_citizen_id_is_present_require_the_date()
    {
        $student = factory(Student::class)->make([
            'citizen_id' => 1234567890,
            'citizen_id_validity' => null,
        ])->toArray();
        $response = $this->post('/student', $student);
        $response->assertSessionHasErrors(['citizen_id_validity']);

        $student = factory(Student::class)->make([
            'citizen_id' => null,
            'citizen_id_validity' => null,
        ])->toArray();
        $response = $this->post('/student', $student);
        $response->assertOk();
        $response->assertSessionHasNoErrors();
    }

    /** @test */
    public function a_guest_cannot_create_a_student()
    {
        $student = factory(Student::class)->make()->toArray();

        $this->be(new User())->post('/student', $student);

        $this->assertDatabaseMissing('students', $student);
        $this->assertCount(0, student::all());
    }
}
