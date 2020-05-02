<?php

namespace App\Http\Controllers;

use App\Enrollment;

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
}
