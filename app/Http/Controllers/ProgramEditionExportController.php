<?php

namespace App\Http\Controllers;

use App\Exports\ProgramEdition\ProgramEditionExport;
use App\ProgramEdition;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProgramEditionExportController extends Controller
{
    public function __invoke(ProgramEdition $programEdition, Request $request)
    {
        $options = $request->validate([
            'cover' => 'sometimes|bool',
            'students' => 'sometimes|bool',
        ]);

        return Excel::download(
            new ProgramEditionExport($programEdition, $options),
            "$programEdition->id.xlsx"
        );
    }
}
