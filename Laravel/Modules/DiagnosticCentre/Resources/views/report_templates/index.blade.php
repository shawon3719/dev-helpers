@extends('diagnosticcentre::layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('diagnosticcentre::cruds.report-template.title_singular') }} {{ trans('global.list') }}
        @can('diagnostic_report_templates_create')
            <a class="btn-sm btn-success" style="float: right" href="{{ route('diagnostic-centre.report-templates.create') }}">
                {{ trans('global.add') }} {{ trans('diagnosticcentre::cruds.report-template.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="server-side-datatable" data-url="{{ route('diagnostic-centre.report-templates.index') }}" class="table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.report-template.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.report-template.fields.name') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.report-template.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.report-template.fields.name') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
<script>

    let delete_data = {
        permission: "diagnostic_report_templates_delete",
        url: "{{ route('diagnostic-centre.report-templates.massDestroy') }}"
    };

    let coloumn_data = [
        {data: 'select'},
        {data: 'code'},
        {data: 'name'},
        {data: 'action', orderable: false, searchable: false},
    ];

</script>
@endsection