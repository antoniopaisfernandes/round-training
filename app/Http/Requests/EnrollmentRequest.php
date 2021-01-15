<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (! $this->has('company_id')) {
            $this->offsetSet(
                'company_id',
                Student::findOrFail($this->get('student_id'))->current_company_id
            );
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'student_id' => 'exists:students,id',
            'program_edition_id' => 'exists:program_editions,id',
            'company_id' => 'exists:companies,id',
        ];
    }
}
