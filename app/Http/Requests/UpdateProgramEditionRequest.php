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
        return $this->user()->can('update', $this->route('program_edition'));
    }
}
