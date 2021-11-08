<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class CustomerMetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows(['customer_meta_create']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id'         => [
                'required'
            ],
            'name'         => [
                'required'
            ],
            'value'         => [
                'required'
            ],
        ];
    }
}
