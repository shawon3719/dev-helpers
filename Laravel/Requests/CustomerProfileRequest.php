<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class CustomerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['customer_profile_create','customer_profile_edit']);
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
            'email'         => [
                'sometimes',
                'email',
                'required',
                // 'unique:users,email,' . request()->id,
            ],
            'contact_person_name'         => [
                'required'
            ],
            'contact_person_email'         => [
                'required', 
                'email'
            ],
            'customer_since'         => [
                'required', 
                'date'
            ],
            'website'         => [
                'nullable',
                'url'
            ],
            'area'         => [
                'nullable',
            ],
            'address'         => [
                'required', 
                'string', 
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
            ],
            'NID'         => [
                'nullable', 
                'numeric', 
            ],
            'gender'         => [
                'nullable', 
                'string', 
            ]
        ];
    }
}
