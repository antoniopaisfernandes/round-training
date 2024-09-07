<?php

namespace App\Http\Requests;

use App\Rules\EnsureAtLeastOneAdmin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('roles') && is_array($this->roles)) {
            $this->offsetSet('roles', collect($this->get('roles'))->map(function ($role) {
                if (is_array($role)) {
                    return $role;
                }

                return Role::findOrFail($role)->toArray();
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user)],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'roles' => ['sometimes', 'array', new EnsureAtLeastOneAdmin($this->user)],
            'permissions' => ['sometimes', 'array'],
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
            parent::validated(),
            ['roles', 'permissions']
        );
    }
}
