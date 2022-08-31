<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateCompanyRequest extends FormRequest
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
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'name' => ['sometimes', 'string', 'max:200'],
            'email' => ['sometimes', 'email', Rule::unique('companies')->ignore($this->company)],
            'website' => ['sometimes', 'url',  Rule::unique('companies')->ignore($this->company)],
        ];
    }
}
