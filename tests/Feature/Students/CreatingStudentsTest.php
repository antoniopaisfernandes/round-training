<?php

namespace Tests\Feature\Students;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatingStudentsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_can_create_a_student()
    {
        $this->withoutExceptionHandling();

        $student = Student::factory()->make()->toArray();

        $response = $this->post('/students', $student);

        $response->assertCreated();
        $this->assertDatabaseHas('students', $student);
    }

    /** @test */
    public function a_guest_cannot_create_a_student()
    {
        $student = Student::factory()->make()->toArray();

        $this->be(new User())->post('/students', $student);

        $this->assertDatabaseMissing('students', $student);
        $this->assertCount(0, student::all());
    }

    /** @test */
    public function it_creates_students_with_user_leader()
    {
        $student = Student::factory()->make([
            'leader_id' => auth()->user()->id,
        ])->toArray();

        $this->post('/students', $student)->assertCreated();

        $this->assertDatabaseHas('students', $student);
    }

    /** @test */
    public function a_name_is_required_for_a_student()
    {
        $student = Student::factory()->make([
            'name' => null,
        ])->toArray();

        $response = $this->post('/students', $student);

        $response->assertSessionHasErrors(['name']);
    }

    /** @test */
    public function an_email_is_required_for_a_student()
    {
        $student = Student::factory()->make([
            'email' => null,
        ])->toArray();

        $response = $this->post('/students', $student);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function when_storing_a_student_if_the_citizen_info_is_present_require_it()
    {
        $student = Student::factory()->withCitizenInformation()->make([
            'citizen_id' => null,
        ])->toArray();
        $response = $this->post('/students', $student);
        $response->assertSessionHasErrors(['citizen_id']);

        $student = Student::factory()->withCitizenInformation()->make([
            'citizen_id_validity' => null,
        ])->toArray();
        $response = $this->post('/students', $student);
        $response->assertSessionHasErrors(['citizen_id_validity']);

        $student = Student::factory()->withoutCitizenInformation()->make()->toArray();
        $response = $this->post('/students', $student);
        $response->assertCreated();
        $response->assertSessionHasNoErrors();
    }
}
