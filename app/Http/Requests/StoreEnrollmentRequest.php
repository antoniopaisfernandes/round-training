<?php

namespace App\Http\Requests;

use App\ProgramEdition;
use App\Student;

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
                ProgramEdition::findOrFail($this->input('program_edition_id'))->starts_at >= today()
                && $this->user()->is(Student::with(['company.coordinator'])->findOrFail($this->input('student_id'))->company->coordinator)
            );
    }
}
