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
            'teacher_name' => 'required',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
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
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        return Arr::except(
            array_merge(
                parent::validated(),
                [
                    'created_by' => auth()->user()->id,
                ]
            ),
            ['schedules']
        );
    }
}
