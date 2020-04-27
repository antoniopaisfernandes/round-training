<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Queries\Sorts\CompanyNameSort;
use App\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\View\View;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResource|View
     */
    public function index()
    {
        request()->validate([
            'limit' => 'sometimes|int',
        ]);

        $students = StudentResource::collection(
            QueryBuilder::for(Student::class)
                ->select('students.*')
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
                ->allowedSorts([
                    'id',
                    'name',
                    AllowedSort::custom('company.name', new CompanyNameSort('students', 'current_company_id'))->defaultDirection('asc'),
                ])
                ->paginate(! request()->has('limit') ? 10 : (request()->get('limit') < 0 ? 9999 : request()->get('limit')))
                ->appends(request()->query())
        );

        return request()->expectsJson()
            ? $students
            : view('student.index', [
                'students' => $students,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
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
            'leader_id' => 'nullable|exists:App\User,id',
        ]);

        $student = Student::create($validated);

        return $this->show($student);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return JsonResponse|JsonResource
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
     * @return JsonResponse
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
            'leader_id' => 'nullable|exists:App\User,id',
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
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Student $student)
    {
        $this->authorize('destroy');

        $student->delete();

        return response()->json();
    }
}
