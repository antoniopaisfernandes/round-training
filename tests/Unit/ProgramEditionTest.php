<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Program;
use App\Models\ProgramEdition;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramEditionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_full_name_with_program_name_and_edition_name()
    {
        $programEdition = ProgramEdition::factory()->create([
            'program_id' => Program::factory()->create([
                'name' => 'Course name',
            ]),
            'name' => 'Mar 2020',
        ]);

        $this->assertEquals(
            'Course name - Mar 2020',
            $programEdition->fullName
        );
    }

    /** @test */
    public function a_program_edition_without_students_gets_its_cost_in_the_assigned_company()
    {
        $programEdition = ProgramEdition::factory()->create([
            'cost' => 105.33,
        ]);

        $splitedCosts = $programEdition->splited_costs;

        $this->assertEquals(1, $splitedCosts->count());
        $this->assertTrue($splitedCosts->first()->is($programEdition->company));
        $this->assertEquals(105.33, $splitedCosts->first()->cost);
    }

    /** @test */
    public function a_program_edition_has_its_costs_weighted_by_the_number_of_students_of_each_company()
    {
        $programEdition = ProgramEdition::factory()->create([
            'cost' => 105.33,
        ]);
        $company1 = Company::factory()->create();
        $twoStudents = Student::factory()->times(2)->create([
            'current_company_id' => $company1->id,
        ]);
        $company2 = Company::factory()->create();
        $threeStudents = Student::factory()->times(3)->create([
            'current_company_id' => $company2->id,
        ]);

        $programEdition->enroll($twoStudents);
        $programEdition->enroll($threeStudents);

        $splitedCosts = $programEdition->splited_costs;
        $this->assertEquals(2, $splitedCosts->count());
        $this->assertTrue($splitedCosts->first()->is($company1));
        $this->assertEquals(round(105.33 * 2 / 5, 2), $splitedCosts->first()->cost);
        $this->assertTrue($splitedCosts->last()->is($company2));
        $this->assertEquals(round(105.33 * 3 / 5, 2), $splitedCosts->last()->cost);
    }

    /** @test */
    public function it_can_fetch_editions_that_are_due_to_evaluate()
    {
        ProgramEdition::factory()->withStudents(2)->create([
            'evaluation_notification_date' => today()->subDay(),
        ]);
        ProgramEdition::factory()->create([
            'evaluation_notification_date' => today()->addDay(),
        ]);

        $this->assertCount(1, ProgramEdition::dueToEvaluate()->get());
    }

    /** @test */
    public function when_no_students_are_enrolled_its_not_due()
    {
        ProgramEdition::factory()->withoutStudents()->create([
            'evaluation_notification_date' => today()->subDay(),
        ]);

        $this->assertCount(0, ProgramEdition::dueToEvaluate()->get());
    }

    /** @test */
    public function when_the_evaluation_are_filled_they_are_not_due()
    {
        $programEditionWithEvaluations = ProgramEdition::factory()->withEvaluations(2)->create([
            'evaluation_notification_date' => today()->subDay(),
        ]);
        $programEditionWithoutEvaluations = ProgramEdition::factory()->withStudents(3)->create([
            'evaluation_notification_date' => today()->subDay(),
        ]);

        $due = ProgramEdition::dueToEvaluate()->get();
        $this->assertCount(1, $due);
        $this->assertTrue($programEditionWithoutEvaluations->is($due->first()));
    }
}
