<?php

namespace App\Http\Controllers;

use App\Enrollment;
use App\Http\Resources\StudentResource;
use App\ProgramEdition;
use App\Student;
use Illuminate\Http\Request;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
