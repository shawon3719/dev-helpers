<?php

namespace Modules\DiagnosticCentre\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('diagnostic_pathology_bills_create');
    }

    public function rules()
    {
        return [
            'code' => [
                'required',
            ],
            'patient_id' => [
                'required',
            ],
            'referrer_id' => [
                'required',
            ],
            'bill_date' => [
                'required',
            ],
            'delivery_date' => [
                'required',
            ],
            'delivery_time' => [
                'required',
            ],
            'item_id' => [
                'required',
            ]
        ];
    }
}