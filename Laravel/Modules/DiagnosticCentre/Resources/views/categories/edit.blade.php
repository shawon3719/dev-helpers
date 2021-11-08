@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('diagnosticcentre::cruds.category.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("diagnostic-centre.categories.update", $category->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="code">{{ trans('diagnosticcentre::cruds.category.fields.code') }}</label>
                        <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" readonly type="text" name="code" id="code" value="{{ old('code', $category->code) }}" required>
                        @if($errors->has('code'))
                            <span class="text-danger">{{ $errors->first('code') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.category.fields.code_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-8 col-md-8 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('diagnosticcentre::cruds.category.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.category.fields.name_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-9 col-md-9 col-sm-12">
                    <div class="form-group">
                        <label class="" for="parent_id">{{ trans('diagnosticcentre::cruds.category.fields.parent_id') }}</label>
                        <select class="form-control select2 {{ $errors->has('parent_id') ? 'is-invalid' : '' }}" name="parent_id" id="parent_id">
                            <option value="" > Select an option..</option>
                            @foreach ($categories as $cat)
                                <option value="{{$cat->id}}" {{$category->parent_id == $cat->id ? 'selected' : ''}}><span style="font-weight: bold!important">{{$cat->name}}</span></option>
                                @foreach ($cat->children as $child)
                                    <option value="{{$child->id}}" {{$category->parent_id == $child->id ? 'selected' : ''}}>&nbsp;&nbsp;↦ {{$child->name}}</option>
                                        @foreach ($child->children as $grandchild)
                                            <option value="{{$grandchild->id}}" {{$category->parent_id == $grandchild->id ? 'selected' : ''}}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;↳ {{$grandchild->name}}</option>
                                            @foreach ($grandchild->children as $grandgrandchild)
                                                <option value="{{$grandgrandchild->id}}" {{$category->parent_id == $grandgrandchild->id ? 'selected' : ''}}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;→ {{$grandgrandchild->name}}</option>
                                            @endforeach
                                        @endforeach
                                @endforeach
                            @endforeach
                        </select>
                        @if($errors->has('parent_id'))
                            <span class="text-danger">{{ $errors->first('parent_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.category.fields.parent_id_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-3 col-md-3 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="type">{{ trans('diagnosticcentre::cruds.category.fields.type') }}</label>
                        <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                            <option value="" > Select an option..</option>
                            @foreach (Config::get('diagnosticcentre.category_type') as $key=>$cat_name)
                                <option value="{{$key}}" {{$category->type == $key ? 'selected' : ''}}>{{$cat_name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                            <span class="text-danger">{{ $errors->first('type') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.category.fields.type_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="" for="description">{{ trans('diagnosticcentre::cruds.category.fields.description') }}</label>
                        <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description" id="description" value="{{ old('description', $category->description) }}" >
                        @if($errors->has('description'))
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.category.fields.description_helper') }}</span>
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