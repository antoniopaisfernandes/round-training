<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Student;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = StudentResource::collection(
            QueryBuilder::for(Student::class)
                ->allowedFilters([
                    AllowedFilter::exact('id'),
                    'name',
                    AllowedFilter::scope('not_enrolled'),
                ])
                ->allowedIncludes([
                    'company',
                    'enrollments',
                    'enrolled_program_editions',
                ])
                ->defaultSort('name')
                ->allowedSorts(['id', 'name'])
                ->paginate(20)
                ->appends(request()->query())
        );

        if (request()->expectsJson()) {
            return $students;
        } else {
            return view('student.index', [
                'students' => $students,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $this->authorize('store');

        $validated = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required|max:10',
            'city' => 'required',
            'citizen_id' => 'sometimes|required',
            'citizen_id_validity' => 'sometimes|required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'birth_place' => 'nullable',
            'nationality' => 'nullable',
            'current_job_title' => 'nullable',
            'current_company_id' => 'nullable',
        ]);

        $student = Student::create($validated);

        return $this->show($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function show(Student $student)
    {
        return StudentResource::make($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, Student $student)
    {
        $this->authorize('update');

        $validated = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'postal_code' => 'required|max:10',
            'city' => 'required',
            'citizen_id' => 'sometimes|required',
            'citizen_id_validity' => 'sometimes|required',
            'email' => 'required|email',
            'phone' => 'nullable',
            'birth_place' => 'nullable',
            'nationality' => 'nullable',
            'current_job_title' => 'nullable',
            'current_company_id' => 'nullable',
        ]);

        $student->update(
            $validated
        );

        return $this->show($student->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Student $student)
    {
        $this->authorize('destroy');

        $student->delete();

        return response()->json();
    }
}
