@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('diagnosticcentre::cruds.report-template.title') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.report-templates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.report-template.fields.code') }}
                        </th>
                        <td>
                            {{ $reportTemplate->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.report-template.fields.name') }}
                        </th>
                        <td>
                            {{ $reportTemplate->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.report-template.fields.template') }}
                        </th>
                        <td>
                            <div class="document-editor__editable">
                                @php
                                    echo($reportTemplate->template);
                                @endphp
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.report-templates.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection