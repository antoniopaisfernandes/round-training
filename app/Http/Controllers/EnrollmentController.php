<?php

namespace App\Http\Controllers;

use App\Enrollment;
use App\Http\Requests\StoreEnrollmentRequest;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
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
}
