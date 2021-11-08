<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['company_create','company_edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => [
                'required',
                'string'
            ],
            'unique_name'         => [
                'sometimes',
                'required', 
                'string', 
                'unique:companies,unique_name,' . request()->id,
            ],
            'type_of_business'         => [
                'required'
            ],
            'address'         => [
                'required', 
                'string'
            ],
            'city'         => [
                'required', 
                'string', 
                'max:255'
            ],
            'state'         => [
                'nullable', 
                'string', 
                'max:255'
            ],
            'zip_code'         => [
                'nullable', 
                'string', 
                'max:30'
            ],
            'primary_phone'         => [
                'required', 
                'numeric', 
            ],
            'alt_phone'         => [
                'nullable', 
                'numeric', 
            ]
        ];
    }
}
