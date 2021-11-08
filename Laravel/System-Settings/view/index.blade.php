@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.system') }} {{ trans('global.settings') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        @can('settings_create')
            <form method="POST" action="{{ route("admin.settings.store") }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label class="required mb-2" for="system_logo">{{ trans('cruds.settings.fields.system_logo') }}</label><span><img width="80" style="margin-left: 15px" src="{{isset($settings['system_logo']) ?  asset(Storage::url($settings['system_logo'])) : ''}}" alt="system_logo"></span>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupFile01"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control{{ $errors->has('system_logo') ? 'is-invalid' : '' }}" name="system_logo" value="{{ old('system_logo', isset($settings['system_logo']) ? $settings['system_logo'] : '') }}" id="system_logo">
                        </div>
                        @if($errors->has('system_logo'))
                            <span class="text-danger">{{ $errors->first('system_logo') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.system_logo_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <label class="required mb-2" for="system_title">{{ trans('cruds.settings.fields.system_title') }}</label>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control {{ $errors->has('system_title') ? 'is-invalid' : '' }}" name="system_title" id="system_title" value="{{ old('system_title', isset($settings['system_title']) ? $settings['system_title'] : '') }}" required placeholder="Enter system title..">
                            <div class="form-control-icon">
                                <i class="bi bi-card-heading"></i>
                            </div>
                        </div>
                        @if($errors->has('system_title'))
                            <span class="text-danger">{{ $errors->first('system_title') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.system_title_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class="required mb-2" for="favicon">{{ trans('cruds.settings.fields.favicon') }}</label><span><img width="20" style="margin-left: 15px" src="{{ isset($settings['favicon']) ?  asset(Storage::url($settings['favicon'])) : ''}}" alt="favicon"></span>
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupFile01"><i class="bi bi-upload"></i></label>
                            <input type="file" class="form-control{{ $errors->has('favicon') ? 'is-invalid' : '' }}" name="favicon" value="{{ old('favicon', isset($settings['favicon']) ? $settings['favicon'] : '') }}" id="favicon">
                        </div>
                        @if($errors->has('favicon'))
                            <span class="text-danger">{{ $errors->first('favicon') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.favicon_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class="mb-2" for="data_order">{{ trans('cruds.settings.fields.data_order') }}</label>
                        <select class="form-control select2 {{ $errors->has('data_order') ? 'is-invalid' : '' }}" name="data_order" id="data_order" >
                            <option value="" >Select an option..</option>
                            @foreach(Config::get('settings.data_order'); as $key => $data_order)
                                <option value="{{ $key }}" {{isset($settings['data_order']) && $key == $settings['data_order'] ? 'selected' : ''}} >{{ $data_order }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('data_order'))
                            <span class="text-danger">{{ $errors->first('data_order') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.data_order_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class="mb-2" for="item_per_page">{{ trans('cruds.settings.fields.item_per_page') }}</label>
                        <select class="form-control select2 {{ $errors->has('item_per_page') ? 'is-invalid' : '' }}" name="item_per_page" id="item_per_page" >
                            <option value="" >Select an option..</option>
                            @foreach(Config::get('settings.item_per_page'); as $key => $item)
                                <option value="{{ $key }}" {{ isset($settings['item_per_page']) && $key == $settings['item_per_page'] ? 'selected' : ''}} >{{ $item }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('item_per_page'))
                            <span class="text-danger">{{ $errors->first('item_per_page') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.item_per_page_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class="required mb-2" for="address">{{ trans('cruds.settings.fields.address') }}</label>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address" value="{{ old('address', isset($settings['address']) ? $settings['address'] : '') }}" required placeholder="Enter system address..">
                            <div class="form-control-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </div>
                        @if($errors->has('address'))
                            <span class="text-danger">{{ $errors->first('address') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.address_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class="required mb-2" for="phone">{{ trans('cruds.settings.fields.phone') }}</label>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" name="phone" id="phone" value="{{ old('phone', isset($settings['phone']) ? $settings['phone'] : '') }}" required placeholder="Enter system phone..">
                            <div class="form-control-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                        </div>
                        @if($errors->has('phone'))
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.phone_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class="required mb-2" for="email">{{ trans('cruds.settings.fields.email') }}</label>
                        <div class="form-group position-relative has-icon-left">
                            <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" id="email" value="{{ old('email', isset($settings['email']) ? $settings['email'] : '') }}" required placeholder="Enter system email..">
                            <div class="form-control-icon">
                                <i class="far fa-envelope"></i>
                            </div>
                        </div>
                        @if($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.email_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class=" mb-2" for="currency">{{ trans('cruds.settings.fields.currency') }}</label>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control {{ $errors->has('currency') ? 'is-invalid' : '' }}" name="currency" id="currency" value="{{ old('currency', isset($settings['currency']) ? $settings['currency'] : '') }}"  placeholder="e.g. BDT">
                            <div class="form-control-icon">
                                <i class="fas fa-money-bill-alt"></i>
                            </div>
                        </div>
                        @if($errors->has('currency'))
                            <span class="text-danger">{{ $errors->first('currency') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.currency_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class=" mb-2" for="currency_symbol">{{ trans('cruds.settings.fields.currency_symbol') }}</label>
                        <div class="form-group position-relative has-icon-left">
                            <input type="text" class="form-control {{ $errors->has('currency_symbol') ? 'is-invalid' : '' }}" name="currency_symbol" id="currency_symbol" value="{{ old('currency_symbol', isset($settings['currency_symbol']) ? $settings['currency_symbol'] : '') }}"  placeholder="e.g. ৳">
                            <div class="form-control-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                        </div>
                        @if($errors->has('currency_symbol'))
                            <span class="text-danger">{{ $errors->first('currency_symbol') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.currency_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-sm-12">
                        <label class="mb-2" for="date_format">{{ trans('cruds.settings.fields.date_format') }}</label>
                        <select class="form-control select2 {{ $errors->has('date_format') ? 'is-invalid' : '' }}" name="date_format" id="date_format" >
                            <option value="" >Select an option..</option>
                            @foreach(Config::get('settings.date_format'); as $key => $item)
                                <option value="{{ $key }}" {{ isset($settings['date_format']) && $key == $settings['date_format'] ? 'selected' : ''}} >{{ $item }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('date_format'))
                            <span class="text-danger">{{ $errors->first('date_format') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.settings.fields.date_format_helper') }}</span>
                    </div>

                    <div class="form-group col-lg-12 col-sm-12 col-md-12 mt-2">
                        <button class="btn btn-success" style="float: right" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </div>
            </form>
        @endcan

    </div>
</div>
@endsection
