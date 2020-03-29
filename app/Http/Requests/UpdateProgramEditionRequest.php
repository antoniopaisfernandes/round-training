<?php

namespace App\Http\Requests;

class UpdateProgramEditionRequest extends ProgramEditionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update');
    }
}
