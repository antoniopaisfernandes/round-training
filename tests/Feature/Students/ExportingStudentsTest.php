<?php

namespace Tests\Feature\Students;

use App\Exports\Student\CoverPageExport;
use App\Exports\Student\ProgramEditionsExport;
use App\Exports\Student\StudentExport;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportingStudentsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $this->be($this->createAdminUser());

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function it_exports_an_excel()
    {
        Excel::fake();
        $student = factory(Student::class)
                        ->state('with-citizen-information')
                        ->state('with-3-program-editions')
                        ->create();

        $this->get("/students/{$student->id}/export");

        Excel::assertDownloaded("{$student->id}.xlsx");
    }

    /** @test */
    public function the_student_export_has_a_cover_page_and_a_program_editions_list()
    {
        Excel::fake();
        $student = factory(Student::class)
                        ->state('with-citizen-information')
                        ->state('with-3-program-editions')
                        ->create();

        $this->get("/students/{$student->id}/export");

        Excel::assertDownloaded("{$student->id}.xlsx", function (StudentExport $export) {
            return $export->sheets()[0] instanceof CoverPageExport
                && $export->sheets()[1] instanceof ProgramEditionsExport;
        });
    }

    /** @test */
    public function the_student_export_has_a_cover_page_and_a_program_edition_list_sheets_with_correct_names()
    {
        $student = factory(Student::class)
                        ->state('with-citizen-information')
                        ->state('with-3-program-editions')
                        ->create();

        $file = $this->get("/students/{$student->id}/export")
            ->baseResponse
            ->getFile()
            ->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);

        $this->assertTrue($spreadsheet->sheetNameExists('Student'));
        $this->assertTrue($spreadsheet->sheetNameExists('Program Editions'));
    }

    /** @test */
    public function the_student_export_cover_page_has_all_the_needed_data()
    {
        $student = factory(Student::class)
                        ->state('with-citizen-information')
                        ->state('with-3-program-editions')
                        ->create();

        $file = $this->get("/students/{$student->id}/export")
                    ->baseResponse
                    ->getFile()
                    ->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);

        $this->assertTrue($spreadsheet->sheetNameExists('Student'));
        $contents = $spreadsheet->getSheet(0)->rangeToArray("A1:C8");

        $this->assertEquals('Name', $contents[0][0]);
        $this->assertEquals($student->name, $contents[0][1]);
        $this->assertEquals('Address', $contents[1][0]);
        $this->assertEquals($student->address, $contents[1][1]);
        $this->assertEquals('Postal code', $contents[2][0]);
        $this->assertEquals($student->postal_code, $contents[2][1]);
        $this->assertEquals('City', $contents[3][0]);
        $this->assertEquals($student->city, $contents[3][1]);
        $this->assertEquals('Company', $contents[4][0]);
        $this->assertEquals($student->company->name, $contents[4][1]);
        $this->assertEquals('Job title', $contents[5][0]);
        $this->assertEquals($student->current_job_title, $contents[5][1]);
        $this->assertNull($contents[6][0]); // No more data
    }

    /** @test */
    public function the_student_export_program_edition_page_has_all_the_courses()
    {
        Excel::fake();
        $student = factory(Student::class)
                        ->state('with-3-program-editions')
                        ->create();

        $this->get("/students/{$student->id}/export");

        Excel::assertDownloaded("{$student->id}.xlsx", function (StudentExport $export) {
            $programEdtionsExport = $export->sheets()[1];

            return $programEdtionsExport->collection()->count() == 3;
        });
    }

    /** @test */
    public function it_can_export_only_the_students_sheet()
    {
        Excel::fake();
        $student = factory(Student::class)
                        ->state('with-3-program-editions')
                        ->create();

        $this->get("/students/{$student->id}/export?cover=false");

        Excel::assertDownloaded("{$student->id}.xlsx", function (StudentExport $export) {
            $this->assertCount(1, $export->sheets());

            return $export->sheets()[0]->collection()->count() == 3;
        });
    }

    /** @test */
    public function it_can_export_only_the_cover_page_sheet()
    {
        $student = factory(Student::class)
                        ->state('with-3-program-editions')
                        ->create();

        $file = $this->get("/students/{$student->id}/export?program_editions=false")
                    ->baseResponse
                    ->getFile()
                    ->getPathname();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($file);

        $this->assertTrue($spreadsheet->sheetNameExists('Student'));
        $this->assertFalse($spreadsheet->sheetNameExists((new ProgramEditionsExport($student))->title()));
    }
}
