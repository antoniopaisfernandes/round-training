<?php

namespace App\Http\Controllers;

use App\Exports\Student\StudentExport;
use App\Http\Middleware\ConvertsToBoolean;
use App\Models\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(ConvertsToBoolean::class);
    }

    public function __invoke(Student $student, Request $request)
    {
        $options = $request->validate([
            'cover' => 'sometimes|boolean',
            'program_editions' => 'sometimes|boolean',
        ]);

        return Excel::download(
            new StudentExport($student, $options),
            "$student->id.xlsx"
        );
    }
}
