<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => 'required',
            'short_name' => 'nullable|string|max:10',
            'coordinator_id' => 'nullable|exists:users,id',
            'vat_number' => 'required',
            'budgets' => 'sometimes|array',
            'budgets.*.company_id' => 'nullable|integer',
            'budgets.*.year' => 'required|integer|min:1990|max:2100|distinct',
            'budgets.*.budget' => 'required|numeric|min:0',
        ];
    }
}
