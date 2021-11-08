@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('diagnosticcentre::cruds.item.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("diagnostic-centre.items.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="code">{{ trans('diagnosticcentre::cruds.item.fields.code') }}</label>
                        <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" readonly type="text" name="code" id="code" value="{{ old('code', $item_code) }}" required>
                        @if($errors->has('code'))
                            <span class="text-danger">{{ $errors->first('code') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.item.fields.code_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-8 col-md-8 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('diagnosticcentre::cruds.item.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.item.fields.name_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-9 col-md-9 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="category_id">{{ trans('diagnosticcentre::cruds.item.fields.category_id') }}</label>
                        <select class="form-control select2 {{ $errors->has('category_id') ? 'is-invalid' : '' }}" name="category_id" id="category_id" required>
                            <option value="" > Select an option..</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}"><span style="font-weight: bold!important">{{$category->name}}</span></option>
                                @foreach ($category->children as $child)
                                    <option value="{{$child->id}}">&nbsp;&nbsp;↦ {{$child->name}}</option>
                                        @foreach ($child->children as $grandchild)
                                            <option value="{{$grandchild->id}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ {{$grandchild->name}}</option>
                                            @foreach ($grandchild->children as $grandgrandchild)
                                                <option value="{{$grandgrandchild->id}}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→ {{$grandgrandchild->name}}</option>
                                            @endforeach
                                        @endforeach
                                @endforeach
                            @endforeach
                        </select>
                        @if($errors->has('category_id'))
                            <span class="text-danger">{{ $errors->first('category_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.item.fields.category_id_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="" for="exp_date">{{ trans('diagnosticcentre::cruds.item.fields.exp_date') }}</label>
                        <div class="input-group ">
                            <input type="text" name="exp_date" value="{{ old('exp_date', '') }}" autocomplete="off" class="datepicker-here form-control digits">
                        </div>
                        @if($errors->has('exp_date'))
                            <span class="text-danger">{{ $errors->first('exp_date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.item.fields.exp_date_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="price">{{ trans('diagnosticcentre::cruds.item.fields.price') }}</label>
                        <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" type="text" name="price" id="price" value="{{ old('price', '') }}" required>
                        @if($errors->has('price'))
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.item.fields.price_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="" for="offer_price">{{ trans('diagnosticcentre::cruds.item.fields.offer_price') }}</label>
                        <input class="form-control {{ $errors->has('offer_price') ? 'is-invalid' : '' }}" type="text" name="offer_price" id="offer_price" value="{{ old('offer_price', '') }}" >
                        @if($errors->has('offer_price'))
                            <span class="text-danger">{{ $errors->first('offer_price') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.item.fields.offer_price_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="" for="description">{{ trans('diagnosticcentre::cruds.item.fields.description') }}</label>
                        <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', '') }}" >
                        @if($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.item.fields.description_helper') }}</span>
                    </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection