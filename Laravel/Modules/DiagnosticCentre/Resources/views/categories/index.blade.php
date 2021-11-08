@extends('diagnosticcentre::layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('diagnosticcentre::cruds.category.title_singular') }} {{ trans('global.list') }}
        @can('diagnostic_categories_create')
            <a class="btn-sm btn-primary" style="float: right; margin-left: 5px" href="{{ route('diagnostic-centre.categories.import') }}">
                <i class="fas fa-upload"></i> {{ trans('diagnosticcentre::global.import') }} {{ trans('diagnosticcentre::cruds.category.title_singular') }}
            </a>
            <a class="btn-sm btn-success" style="float: right" href="{{ route('diagnostic-centre.categories.create') }}">
                <i class="fas fa-plus"></i> {{ trans('global.add') }} {{ trans('diagnosticcentre::cruds.category.title_singular') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        @include('diagnosticcentre::categories.incompleteRow')
        <div class="table-responsive">
            <table id="server-side-datatable" data-url="{{ route('diagnostic-centre.categories.index') }}" class=" table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.category.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.category.fields.name') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.category.fields.parent_id') }}</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('diagnosticcentre::cruds.category.fields.code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.category.fields.name') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.category.fields.parent_id') }}</th>
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
        url: "{{ route('diagnostic-centre.categories.massDestroy') }}"
    };

    let coloumn_data = [
        {data: 'select'},
        {data: 'code'},
        {data: 'name'},
        {data: 'parent'},
        {data: 'action', orderable: false, searchable: false},
    ];

</script>
@endsection