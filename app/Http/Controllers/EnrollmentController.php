<?php

namespace App\Http\Controllers;

use App\Enrollment;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Resources\StudentCollectionResource;
use App\Student;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'studentsAbleToEnroll' => StudentCollectionResource::make(Student::canBeEnrolled()->get()),
            'enrollmentsAbleToEdit' => Enrollment::status('enrollable')->get(),
        ];

        return request()->expectsJson() ? $data : view('enrollment.index', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Enrollment  $enrollment
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Enrollment $enrollment)
    {
        return response()->json($enrollment);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreEnrollmentRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(StoreEnrollmentRequest $request)
    {
        $enrollment = Enrollment::create($request->validated());

        return $this->show($enrollment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Enrollment $enrollment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Enrollment $enrollment)
    {
        $this->authorize('delete', $enrollment);

        $enrollment->delete();

        return response()->json();
    }
}
