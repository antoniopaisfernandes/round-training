<?php

namespace App\Http\Controllers;

use App\Enrollment;
use App\Http\Resources\StudentResource;
use App\ProgramEdition;
use App\Student;

class ProgramEditionStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProgramEdition $programEdition)
    {
        return StudentResource::collection($programEdition->students);
    }

    /**
     * Display the specified resource.
     *
     * @param  ProgramEdition $programEdition
     * @param  Student $student
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramEdition $programEdition, Student $student)
    {
        return resolve(EnrollmentController::class)->show(
            Enrollment::where('student_id', $student->id)
                ->where('program_edition_id', $programEdition->id)
                ->firstOrFail()
        );
    }
}
