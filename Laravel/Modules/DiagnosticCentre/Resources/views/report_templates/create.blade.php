@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('diagnosticcentre::cruds.report-template.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("diagnostic-centre.report-templates.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="code">{{ trans('diagnosticcentre::cruds.report-template.fields.code') }}</label>
                        <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" readonly type="text" name="code" id="code" value="{{ old('code', $report_template_code) }}" required>
                        @if($errors->has('code'))
                            <span class="text-danger">{{ $errors->first('code') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.report-template.fields.code_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-8 col-md-8 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('diagnosticcentre::cruds.report-template.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.report-template.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="template">{{ trans('diagnosticcentre::cruds.report-template.fields.template') }}</label>
                        <div class="document-editor">
                            <div class="document-editor__toolbar"></div>
                            <div class="document-editor__editable-container">
                                <div class="document-editor__editable">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="template" name="template" value="">
                        @if($errors->has('template'))
                            <span class="text-danger">{{ $errors->first('template') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.report-template.fields.template_helper') }}</span>
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
@section('scripts')
<script>
    //CK Editor 
DecoupledEditor
  .create( document.querySelector( '.document-editor__editable' ), {} )
  .then( editor => {
      const toolbarContainer = document.querySelector( '.document-editor__toolbar' );

      toolbarContainer.appendChild( editor.ui.view.toolbar.element );

      window.editor = editor;
      
      editor.model.document.on( 'change', () => {
        $('#template').val(editor.getData());
      });
  } )
  .catch( err => {
      console.log( err );
  } );
</script>
@endsection