<?php

namespace Modules\DiagnosticCentre\Http\Requests;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyItemRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('diagnostic_items_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:items,id',
        ];
    }
}
