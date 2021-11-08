<?php

namespace Modules\DiagnosticCentre\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('diagnostic_categories_create');
    }

    public function rules()
    {
        return [
            'code' => [
                'required',
                'string',
            ],
            'name' => [
                'required',
                'string',
            ],
            'type' => [
                'required',
                'string',
            ],
            'parent_id' => [
                'nullable',
                'numeric',
            ],
        ];
    }
}