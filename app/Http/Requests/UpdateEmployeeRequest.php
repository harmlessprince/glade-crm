<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => ['sometimes', 'integer', 'exists:companies,id'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'first_name' => ['sometimes', 'string', 'max:200'],
            'last_name' => ['sometimes', 'string', 'max:200'],
            'email' => ['sometimes', 'email'],
            'phone' => ['sometimes', 'string'],
        ];
    }
}
