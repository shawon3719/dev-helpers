@extends('diagnosticcentre::layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('diagnosticcentre::cruds.pathology-report.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="server-side-datatable" data-url="{{ route('diagnostic-centre.pathology-reports.create') }}" class=" table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.patient_id') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.referrer_id') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.delivery_date') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.patient_id') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.referrer_id') }}</th>
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
        permission: "diagnostic_categories_delete",
        url: ""
    };

    let coloumn_data = [
        {data: 'select'},
        {data: 'code'},
        {data: 'patient'},
        {data: 'referrer'},
        {data: 'delivery_date'},
        {data: 'action', orderable: false, searchable: false},
    ];

</script>
@endsection