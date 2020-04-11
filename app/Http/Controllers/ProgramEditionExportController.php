<?php

namespace App\Http\Controllers;

use App\Exports\ProgramEdition\ProgramEditionExport;
use App\ProgramEdition;
use Maatwebsite\Excel\Facades\Excel;

class ProgramEditionExportController extends Controller
{
    public function __invoke(ProgramEdition $programEdition)
    {
        return Excel::download(
            new ProgramEditionExport($programEdition),
            "$programEdition->id.xlsx"
        );
    }
}
