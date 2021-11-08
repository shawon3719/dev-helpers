<?php

namespace Modules\DiagnosticCentre\Http\Requests;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyReportTemplateRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('diagnostic_report_templates_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:report_templates,id',
        ];
    }
}
