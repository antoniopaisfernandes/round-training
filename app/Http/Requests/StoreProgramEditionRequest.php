<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

class StoreProgramEditionRequest extends ProgramEditionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('store');
    }

    /**
     * Get the validated data from the request.
     *
     * @return array
     */
    public function validated()
    {
        return Arr::except(
            parent::validated(),
            ['schedules']
        );
    }
}
