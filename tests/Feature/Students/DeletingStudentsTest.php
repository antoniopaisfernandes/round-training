<?php

namespace Tests\Feature\Students;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingStudentsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_a_student()
    {
        $student = Student::factory()->create();

        $this->assertCount(1, Student::all());

        $this->actingAs($this->createAdminUser())->delete("/students/{$student->id}");

        $this->assertCount(0, Student::all());
    }

    /** @test */
    public function it_requires_necessary_permissions_to_delete_a_student()
    {
        $userWithoutPermission = User::factory()->create();
        $student = Student::factory()->create();

        $this->assertCount(1, Student::all());

        $this->actingAs($userWithoutPermission)->delete("/students/{$student->id}")->assertStatus(403);

        $this->assertCount(1, Student::all());
    }
}
