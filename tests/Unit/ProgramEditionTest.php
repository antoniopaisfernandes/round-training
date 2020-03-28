<?php

namespace Tests\Unit;

use App\Program;
use App\ProgramEdition;
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
}
