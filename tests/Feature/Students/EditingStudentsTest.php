<?php

namespace Tests\Feature\Students;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EditingStudentsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_can_edit_a_student()
    {
        $this->withoutExceptionHandling();

        $student = factory(Student::class)->state('with-citizen-information')->create([
            'name' => 'Old name',
        ]);
        $updatedStudent = with(
            $student->fresh()->toArray(),
            function ($student) {
                $student['name'] = 'New name';
                return $student;
            }
        );

        $this->patch("/students/{$student->id}", $updatedStudent);

        $this->assertDatabaseHas('students', [
            'name' => 'New name',
        ]);
    }

    /** @test */
    public function a_guest_cannot_updating_a_student()
    {
        $student = factory(Student::class)->create([
            'name' => 'Old name',
        ]);

        $this->be(new User())->patch("/students/{$student->id}", [
            'name' => null,
        ]);

        $this->assertDatabaseHas('students', [
            'name' => 'Old name',
        ]);
    }

    /** @test */
    public function it_can_edit_a_student_with_leader_id()
    {
        $this->withoutExceptionHandling();

        $student = factory(Student::class)->create([
            'leader_id' => factory(User::class)->create()->id,
        ]);
        $updatedStudent = with(
            $student->fresh()->toArray(),
            function ($student) {
                $student['leader_id'] = auth()->user()->id;
                unset($student['citizen_id']);
                unset($student['citizen_id_validity']);
                return $student;
            }
        );

        $this->patch("/students/{$student->id}", $updatedStudent);

        $this->assertDatabaseHas('students', [
            'leader_id' => auth()->user()->id,
        ]);
    }

    /** @test */
    public function a_name_is_required_updating_a_student()
    {
        $student = factory(Student::class)->create([
            'name' => 'Old name',
        ]);

        $response = $this->patch("/students/{$student->id}", [
            'name' => null,
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('students', [
            'name' => 'Old name',
        ]);
    }

    /** @test */
    public function an_email_is_required_updating_a_student()
    {
        $student = factory(Student::class)->create([
            'email' => 'old_email@example.com',
        ]);

        $response = $this->patch("/students/{$student->id}", [
            'email' => null,
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseHas('students', [
            'email' => 'old_email@example.com',
        ]);
    }
}
