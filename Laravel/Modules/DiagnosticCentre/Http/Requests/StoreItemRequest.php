<?php

namespace Modules\DiagnosticCentre\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('diagnostic_items_create');
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
            'price' => [
                'required',
                'numeric',
            ],
            'category_id' => [
                'nullable',
                'numeric',
            ],
        ];
    }
}