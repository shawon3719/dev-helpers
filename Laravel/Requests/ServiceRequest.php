<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['service_create','service_edit']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'               => [
                'required'
            ],
            'category_id'         => [
                'required'
            ],
            'regular_price'       => [
                'required'
            ],
            'allowed_connection'  => [
                'nullable',
                'numeric'
            ],
            // 'visibility'          => [
            //     'required'
            // ]
        ];
    }
}
