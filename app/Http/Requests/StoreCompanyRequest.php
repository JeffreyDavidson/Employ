<?php

namespace App\Http\Requests;

use App\Company;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Company::class);
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
