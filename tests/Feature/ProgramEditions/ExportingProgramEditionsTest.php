<?php

namespace Tests\Feature\ProgramEditions;

use App\Exports\ProgramEditionExport;
use App\ProgramEdition;
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
}
