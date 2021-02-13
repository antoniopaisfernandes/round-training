<?php

namespace App\Http\Requests;

class StoreCompanyRequest extends CompanyRequest
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
