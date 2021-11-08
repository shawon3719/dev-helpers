<?php

namespace Modules\DiagnosticCentre\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportTemplateRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('diagnostic_report_templates_create');
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
            'template' => [
                'required',
            ],
        ];
    }
}