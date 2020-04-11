<?php

namespace Tests\Feature\ProgramEditions;

use App\Exports\ProgramEdition\CoverPageExport;
use App\Exports\ProgramEdition\ProgramEditionExport;
use App\Exports\ProgramEdition\StudentsExport;
use App\ProgramEdition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportingProgramEditionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());
    }

    /** @test */
    public function it_exports_an_excel()
    {
        $this->withoutExceptionHandling();
        Excel::fake();
        $programEdition = factory(ProgramEdition::class)->create();

        $this->get("/program-editions/{$programEdition->id}/export");

        Excel::assertDownloaded("{$programEdition->id}.xlsx");
    }

    /** @test */
    public function the_program_edition_export_has_a_cover_page_and_a_student_list()
    {
        $this->withoutExceptionHandling();
        Excel::fake();
        $programEdition = factory(ProgramEdition::class)->create();

        $this->get("/program-editions/{$programEdition->id}/export");

        Excel::assertDownloaded("{$programEdition->id}.xlsx", function (ProgramEditionExport $export) {
            return $export->sheets()[0] instanceof CoverPageExport
                && $export->sheets()[1] instanceof StudentsExport;
        });
    }

    /** @test */
    public function the_program_edition_export_students_page_has_all_the_students()
    {
        $this->withoutExceptionHandling();
        Excel::fake();
        $programEdition = factory(ProgramEdition::class)->states('with-4-students')->create();

        $this->get("/program-editions/{$programEdition->id}/export");

        Excel::assertDownloaded("{$programEdition->id}.xlsx", function (ProgramEditionExport $export) {
            $studentsExport = $export->sheets()[1];

            return $studentsExport->collection()->count() == 4;
        });
    }
}
