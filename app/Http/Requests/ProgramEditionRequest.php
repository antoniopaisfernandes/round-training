<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class ProgramEditionRequest extends FormRequest
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
        if ($this->has('students') && $this->has('enrollments')) {
            $this->offsetUnset('students');
        }

        elseif ($this->has('students')) {
            $this->offsetSet('enrollments', collect($this->get('students'))->map(function ($student) {
                return [
                    'student_id' => $student['id'],
                    'company_id' => $student['current_company_id'],
                ];
            })->toArray());
            $this->offsetUnset('students');
        }

        if ($this->has('enrollments')) {
            $this->offsetSet('enrollments', collect($this->get('enrollments'))->map(function ($enrollment) {
                unset($enrollment['program_edition']);
                if (array_key_exists('hours_attended', $enrollment)) {
                    if (is_null($enrollment['hours_attended'])) {
                        $enrollment['minutes_attended'] = null;
                    } else {
                        $enrollment['minutes_attended'] = $enrollment['hours_attended'] * 60;
                    }
                    unset($enrollment['hours_attended']);
                }
                return $enrollment;
            })->toArray());
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
            'program_id' => 'required|exists:programs,id',
            'name' => 'required|max:50',
            'company_id' => 'required|exists:companies,id',
            'cost' => 'required|min:0|max:999999',
            'supplier' => 'required',
            'supplier_certifications' => 'nullable|string',
            'teacher_name' => 'required',
            'starts_at' => [
                'nullable',
                'date',
            ],
            'ends_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],
            'evaluation_notification_date' => [
                'nullable',
                'date',
                'after_or_equal:ends_at',
            ],
            'goals' => 'nullable|string',
            'schedules' => 'sometimes|array',
            'schedules.*.starts_at' => [
                'required_with:schedules',
                'date',
                'after_or_equal:starts_at',
                'before_or_equal:ends_at',
            ],
            'schedules.*.ends_at' => [
                'required_with:schedules',
                'date',
                'after_or_equal:schedules.*.starts_at',
            ],
            'schedules.*.interval_start' => [
                'nullable',
                'date',
                'after:schedules.*.starts_at',
                'before:schedules.*.ends_at',
            ],
            'schedules.*.interval_minutes' => [
                'nullable',
                'integer',
                'gt:0',
            ],
            'enrollments' => 'sometimes|array',
            'enrollments.*.program_edition_id' => [
                'nullable',
                'same:id',
            ],
            'enrollments.*.student_id' => [
                'required_with:enrollments',
                'distinct',
                'exists:students,id',
            ],
            'enrollments.*.company_id' => [
                'required_with:enrollments',
                'exists:companies,id',
            ],
            'enrollments.*.global_evaluation' => [
                'nullable',
                'string',
                'max:20',
            ],
            'enrollments.*.evaluation_comments' => [
                'nullable',
                'string',
                'max:255',
            ],
            'enrollments.*.program_should_be_repeated' => [
                'nullable',
                'bool',
            ],
            'enrollments.*.should_be_repeated_in_months' => [
                'nullable',
                'int',
            ],
            'enrollments.*.minutes_attended' => [
                'nullable',
                'int',
                'min:0',
                'max:30000',
            ],
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        return Arr::except(
            array_merge(
                parent::validated(),
                [
                    'created_by' => auth()->user()->id,
                ]
            ),
            ['schedules', 'enrollments']
        );
    }

    /**
     * Get the validated data for a relationship from the request.
     *
     * @return array
     */
    public function validatedRelationship(string $relationship)
    {
        return parent::validated()[$relationship];
    }
}
