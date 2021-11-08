@extends('diagnosticcentre::layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('diagnosticcentre::cruds.pathology-bills.title_singular') }} {{ trans('global.list') }}
        @can('diagnostic_pathology_bills_create')
            <a class="btn-sm btn-success" style="float: right" href="{{ route('diagnostic-centre.pathology-bills.create') }}">
                {{ trans('global.add') }} {{ trans('diagnosticcentre::cruds.pathology-bills.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="server-side-datatable" data-url="{{ route('diagnostic-centre.pathology-bills.index') }}" class=" table table-responsive table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.code') }}</th>
                        <th width="30">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.patient_id') }}</th>
                        <th style="white-space: nowrap">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_date') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.payment_status') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.net_payable') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.paid') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.due_amount') }}</th>
                        <th style="white-space: nowrap">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.billing_date') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.code') }}</th>
                        <th width="30">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.patient_id') }}</th>
                        <th style="white-space: nowrap">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_date') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.payment_status') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.net_payable') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.paid') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.due_amount') }}</th>
                        <th style="white-space: nowrap">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.billing_date') }}</th>
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
        permission: "diagnostic_pathology_bills_delete",
        url: "{{ route('diagnostic-centre.pathology-bills.massDestroy') }}"
    };

    let coloumn_data = [
        {data: 'select'},
        {data: 'code'},
        {data: 'patient'},
        {data: 'delivery_date'},
        {data: 'payment_status'},
        {data: 'total'},
        {data: 'paid'},
        {data: 'due_amount'},
        {data: 'bill_date'},
        {data: 'action', orderable: false, searchable: false},
    ];

</script>
@endsection