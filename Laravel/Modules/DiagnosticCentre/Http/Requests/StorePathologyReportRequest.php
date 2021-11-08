<?php

namespace Modules\DiagnosticCentre\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StorePathologyReportRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('pathology_reports_create');
    }

    public function rules()
    {
        return [
            'code' => [
                'required',
            ],
            'reporting_date' => [
                'required',
            ],
            'report' => [
                'required',
            ]
        ];
    }
}