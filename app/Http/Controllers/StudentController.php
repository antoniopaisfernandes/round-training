<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('student.index', [
            'students' => new StudentResource(Student::paginate(20)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResource
     */
    public function store(Request $request)
    {
        $this->authorize('store');

        $validated = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required|max:10',
            'city' => 'required',
            'citizen_id' => 'nullable',
            'citizen_id_validity' => Rule::requiredIf($request->get('citizen_id')),
            'email' => 'required|email',
            'phone' => 'required',
            'birth_place' => 'nullable',
            'nationality' => 'nullable',
            'current_job_title' => 'nullable',
        ]);

        $student = Student::create($validated);

        return $this->show($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        return new StudentResource($student);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $this->authorize('update');

        $validated = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required|max:10',
            'city' => 'required',
            'citizen_id' => 'nullable',
            'citizen_id_validity' => Rule::requiredIf($request->get('citizen_id')),
            'email' => 'required|email',
            'phone' => 'required',
            'birth_place' => 'nullable',
            'nationality' => 'nullable',
            'current_job_title' => 'nullable',
        ]);

        $student->update(
            $validated
        );

        return $this->show($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $this->authorize('destroy');

        $student->delete();

        return response()->json();
    }
}
