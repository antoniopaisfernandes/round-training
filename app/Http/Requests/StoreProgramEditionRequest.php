<?php

namespace App\Http\Requests;

class StoreProgramEditionRequest extends ProgramEditionRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('store');
    }
}
