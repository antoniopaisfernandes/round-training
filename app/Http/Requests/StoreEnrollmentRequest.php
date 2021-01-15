<?php

namespace App\Http\Requests;

use App\Models\ProgramEdition;
use App\Models\Student;

class StoreEnrollmentRequest extends EnrollmentRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('store_enrollment')
            || (
                ProgramEdition::findOrFail($this->input('program_edition_id'))->status('enrollable')->exists()
                && Student::findOrFail($this->input('student_id'))->canBeEnrolledBy($this->user())->exists()
            );
    }
}
