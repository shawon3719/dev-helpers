@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('diagnosticcentre::cruds.item.title') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.items.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.item.fields.code') }}
                        </th>
                        <td>
                            {{ $item->code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.item.fields.name') }}
                        </th>
                        <td>
                            {{ $item->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.item.fields.category_id') }}
                        </th>
                        <td>
                            {{ $item->category->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.item.fields.price') }}
                        </th>
                        <td>
                            {{ $item->price ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.item.fields.offer_price') }}
                        </th>
                        <td>
                            {{ $item->offer_price ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.item.fields.exp_date') }}
                        </th>
                        <td>
                            {{isset($item->exp_date) ? Carbon\Carbon::parse($item->exp_date)->format($settings['date_format'] ?? 'j F, Y') : 'N/A'}}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('diagnosticcentre::cruds.item.fields.description') }}
                        </th>
                        <td>
                            {{ $item->description }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.items.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection