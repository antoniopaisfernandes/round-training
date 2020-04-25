<?php

namespace Tests\Unit;

use App\Company;
use App\Program;
use App\ProgramEdition;
use App\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgramEditionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_full_name_with_program_name_and_edition_name()
    {
        $programEdition = factory(ProgramEdition::class)->create([
            'program_id' => factory(Program::class)->create([
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
        $programEdition = factory(ProgramEdition::class)->create([
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
        $programEdition = factory(ProgramEdition::class)->create([
            'cost' => 105.33,
        ]);
        $company1 = factory(Company::class)->create();
        $twoStudents = factory(Student::class, 2)->create([
            'current_company_id' => $company1->id,
        ]);
        $company2 = factory(Company::class)->create();
        $threeStudents = factory(Student::class, 3)->create([
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
}
