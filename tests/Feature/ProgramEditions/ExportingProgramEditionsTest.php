<?php

namespace Tests\Feature\ProgramEditions;

use App\Exports\ProgramEdition\CoverPageExport;
use App\Exports\ProgramEdition\ProgramEditionExport;
use App\Exports\ProgramEdition\StudentsExport;
use App\Models\ProgramEdition;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
    public function the_program_edition_export_has_a_cover_page_and_a_student_list_sheets_with_correct_names()
    {
        $this->withoutExceptionHandling();
        $programEdition = factory(ProgramEdition::class)->create();

        $file = $this->get("/program-editions/{$programEdition->id}/export")
            ->baseResponse
            ->getFile()
            ->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);

        $this->assertTrue($spreadsheet->sheetNameExists('Curso'));
        $this->assertTrue($spreadsheet->sheetNameExists('Alunos'));
    }

    /** @test */
    public function the_program_edition_export_cover_page_has_all_the_needed_data()
    {
        $this->withoutExceptionHandling();
        $programEdition = factory(ProgramEdition::class)->create();

        $file = $this->get("/program-editions/{$programEdition->id}/export")
                    ->baseResponse
                    ->getFile()
                    ->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);

        $this->assertTrue($spreadsheet->sheetNameExists('Curso'));
        $contents = $spreadsheet->getSheet(0)->rangeToArray("A1:C8");

        $this->assertEquals('Curso', $contents[0][0]);
        $this->assertEquals($programEdition->full_name, $contents[0][1]);
        $this->assertEquals('Empresa', $contents[1][0]);
        $this->assertEquals($programEdition->company->name, $contents[1][1]);
        $this->assertEquals('Fornecedor', $contents[2][0]);
        $this->assertEquals($programEdition->supplier, $contents[2][1]);
        $this->assertEquals('Formador', $contents[3][0]);
        $this->assertEquals($programEdition->teacher_name, $contents[3][1]);
        $this->assertEquals('Custo', $contents[4][0]);
        $this->assertEquals($programEdition->cost, str_replace(',', '', $contents[4][1]));
        $this->assertEquals('Data início', $contents[5][0]);
        $this->assertEquals($programEdition->starts_at, $contents[5][1]);
        $this->assertEquals('Data fim', $contents[6][0]);
        $this->assertEquals($programEdition->ends_at, $contents[6][1]);
        $this->assertNull($contents[7][0]); // No more data
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

    /** @test */
    public function it_can_export_only_the_students_sheet()
    {
        $this->withoutExceptionHandling();
        Excel::fake();
        $programEdition = factory(ProgramEdition::class)->states('with-4-students')->create();

        $this->get("/program-editions/{$programEdition->id}/export?cover=false");

        Excel::assertDownloaded("{$programEdition->id}.xlsx", function (ProgramEditionExport $export) {
            $this->assertCount(1, $export->sheets());

            return $export->sheets()[0]->collection()->count() == 4;
        });
    }

    /** @test */
    public function it_can_export_only_the_cover_page_sheet()
    {
        $this->withoutExceptionHandling();
        $programEdition = factory(ProgramEdition::class)->states('with-4-students')->create();

        $file = $this->get("/program-editions/{$programEdition->id}/export?students=false")
                    ->baseResponse
                    ->getFile()
                    ->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);

        $this->assertTrue($spreadsheet->sheetNameExists('Curso'));
        $this->assertFalse($spreadsheet->sheetNameExists('Alunos'));
    }
}
