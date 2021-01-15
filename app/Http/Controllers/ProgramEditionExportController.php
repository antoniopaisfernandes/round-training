<?php

namespace App\Http\Controllers;

use App\Exports\ProgramEdition\ProgramEditionExport;
use App\Http\Middleware\ConvertsToBoolean;
use App\Models\ProgramEdition;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProgramEditionExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(ConvertsToBoolean::class);
    }

    public function __invoke(ProgramEdition $programEdition, Request $request)
    {
        $options = $request->validate([
            'cover' => 'sometimes|boolean',
            'students' => 'sometimes|boolean',
        ]);

        return Excel::download(
            new ProgramEditionExport($programEdition, $options),
            "$programEdition->id.xlsx"
        );
    }
}
