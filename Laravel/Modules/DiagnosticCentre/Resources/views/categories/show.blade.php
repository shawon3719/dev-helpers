@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('diagnosticcentre::cruds.category.title') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.category.fields.code') }}
                        </th>
                        <td>
                            {{ $category->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.category.fields.name') }}
                        </th>
                        <td>
                            {{ $category->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.category.fields.parent_id') }}
                        </th>
                        <td>
                            {{ $category->parent->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.category.fields.description') }}
                        </th>
                        <td>
                            {{ $category->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.categories.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection