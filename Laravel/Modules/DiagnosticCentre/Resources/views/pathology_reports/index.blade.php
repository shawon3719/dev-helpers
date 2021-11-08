@extends('diagnosticcentre::layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('diagnosticcentre::cruds.pathology-report.title_singular') }} {{ trans('global.list') }}
        @can('pathology_reports_create')
            <a class="btn-sm btn-success" style="float: right" href="{{ route('diagnostic-centre.pathology-reports.create') }}">
                {{ trans('global.add') }} {{ trans('diagnosticcentre::cruds.pathology-report.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="server-side-datatable" data-url="{{ route('diagnostic-centre.pathology-reports.index') }}" class=" table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.bill_code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.patient_id') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.delivery_date') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.bill_code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.patient_id') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.delivery_date') }}</th>
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
        permission: "pathology_reports_delete",
        url: "{{ route('diagnostic-centre.pathology-reports.massDestroy') }}"
    };

    let coloumn_data = [
        {data: 'select'},
        {data: 'code'},
        {data: 'bill'},
        {data: 'patient'},
        {data: 'delivery_date'},
        {data: 'action', orderable: false, searchable: false},
    ];

</script>
@endsection