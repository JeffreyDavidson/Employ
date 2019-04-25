<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $company = $this->route('company');

        return $this->user()->can('update', $company);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['nullable', 'string', 'email'],
            'logo' => ['nullable', 'image', Rule::dimensions()->maxWidth(100)->maxHeight(100)],
            'website' => ['nullable', 'string'],
        ];
    }
}
