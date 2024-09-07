<?php

namespace Tests\Feature\Enrollment;

use App\Models\Company;
use App\Models\Enrollment;
use App\Models\ProgramEdition;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletingEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_coordinator_can_unenroll_its_students()
    {
        $this->withoutExceptionHandling();

        $coordinator = User::factory()->create();
        $enrollment = Enrollment::factory()->create();
        $enrollment->company->fill(['coordinator_id' => $coordinator->id])->save();
        $this->assertEquals(1, Enrollment::count());

        $this->actingAs($coordinator)
            ->delete("/enrollments/{$enrollment->id}")
            ->assertOk();

        $this->assertEquals(0, Enrollment::count());
    }

    /** @test */
    public function only_admins_and_coordinators_can_unenroll_students()
    {
        $coordinator = User::factory()->create();
        $enrollment = Enrollment::factory()->create();
        $enrollment->company->fill(['coordinator_id' => $coordinator->id])->save();
        $this->assertEquals(1, Enrollment::count());

        $this->actingAs(User::factory()->create())
            ->delete("/enrollments/{$enrollment->id}")
            ->assertForbidden();

        $this->assertEquals(1, Enrollment::count());
    }

    /** @test */
    public function after_a_program_edition_starts_only_admins_can_unenroll_students()
    {
        $coordinator = User::factory()->create();
        $enrollment = Enrollment::factory()->create();
        $enrollment->company->fill(['coordinator_id' => $coordinator->id])->save();
        $enrollment->programEdition->fill([
            'starts_at' => today()->subDay(),
        ])->save();
        $this->assertEquals(1, Enrollment::count());

        $this->actingAs($coordinator)
            ->delete("/enrollments/{$enrollment->id}")
            ->assertForbidden();
        $this->assertEquals(1, Enrollment::count());

        $this->actingAs($this->createAdminUser())
            ->delete("/enrollments/{$enrollment->id}")
            ->assertOk();
        $this->assertEquals(0, Enrollment::count());
    }
}
