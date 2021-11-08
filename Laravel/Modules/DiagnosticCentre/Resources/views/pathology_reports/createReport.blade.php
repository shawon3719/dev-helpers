@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('diagnosticcentre::cruds.pathology-report.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("diagnostic-centre.pathology-reports.store", $pathology_report->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="pathology_billing_id" value="{{$pathology_report->id}}">
            <div class="row">
                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="code">{{ trans('diagnosticcentre::cruds.pathology-report.fields.code') }}</label>
                        <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" readonly type="text" name="code" id="code" value="{{ old('code', $pathology_report_code) }}" required>
                        @if($errors->has('code'))
                            <span class="text-danger">{{ $errors->first('code') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-report.fields.code_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="" for="patient_id">{{ trans('diagnosticcentre::cruds.pathology-report.fields.patient_id') }}</label>
                        <input readonly class="form-control {{ $errors->has('patient_id') ? 'is-invalid' : '' }}" type="text" name="patient_id" id="patient_id" value="{{ old('patient_id', $pathology_report->patient->name) }}">
                        @if($errors->has('patient_id'))
                            <span class="text-danger">{{ $errors->first('patient_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-report.fields.patient_id_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="" for="referrer_id">{{ trans('diagnosticcentre::cruds.pathology-report.fields.referrer_id') }}</label>
                        <input readonly class="form-control {{ $errors->has('referrer_id') ? 'is-invalid' : '' }}" type="text" name="referrer_id" id="referrer_id" value="{{ old('referrer_id', $pathology_report->referrer->name) }}">
                        @if($errors->has('referrer_id'))
                            <span class="text-danger">{{ $errors->first('referrer_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-report.fields.referrer_id_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="reporting_date">{{ trans('diagnosticcentre::cruds.pathology-report.fields.reporting_date') }}</label>
                        <div class="input-group">
                            <input readonly type="text" name="reporting_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" autocomplete="off" class="datepicker-here form-control digits">
                        </div>
                        @if($errors->has('reporting_date'))
                            <span class="text-danger">{{ $errors->first('reporting_date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-report.fields.reporting_date_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="" for="template_id">{{ trans('diagnosticcentre::cruds.pathology-report.fields.template_id') }}</label>
                        <select class="form-control template_id select2 {{ $errors->has('template_id') ? 'is-invalid' : '' }}" name="template_id" id="template_id" >
                            <option value="" > Select an option..</option>
                            @foreach ($report_templates as $report_template)
                                <option value="{{$report_template->id}}">{{$report_template->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('template_id'))
                            <span class="text-danger">{{ $errors->first('template_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-report.fields.template_id_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="report">{{ trans('diagnosticcentre::cruds.pathology-report.fields.report') }}</label>
                        <div style="margin: 10px 0!important;">
                            <input type="radio" id="create" name="report_type" value="create" checked>
                            <label for="create" >Create</label>
                        
                            <input type="radio" id="upload" name="report_type" value="upload">
                            <label for="upload">Upload</label>
                        </div>
                        <div class="document-editor create area">
                            <div class="document-editor__toolbar"></div>
                            <div class="document-editor__editable-container">
                                <div id="document-editor-custom-value" class="document-editor__editable">
                                    @php
                                        echo($pathology_report->report)
                                    @endphp
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="report" name="report" value="">
                        <input class="form-control upload area d-none {{ $errors->has('report') ? 'is-invalid' : '' }}" type="file" name="report" accept=".doc, .docx,.pdf" value="{{ old('report', '') }}">
                        @if($errors->has('report'))
                            <span class="text-danger">{{ $errors->first('report') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-report.fields.report_helper') }}</span>
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
    $('input[type="radio"]').click(function(){
        var inputValue = $('input[type="radio"]:checked').val();
        var targetArea = $("." + inputValue);
        $(".area").not(targetArea).addClass('d-none');
        $(targetArea).removeClass('d-none');
        $('input[name="report"]').val('');
        editor.setData(''); 
    });
</script>
<script>
    $(function () {
    // On change item Select 2
    $('.template_id').on('change', function() {
      
        var template_id = $(this).val();

      $.ajax({
        type: "GET",
        url: "/diagnostic-centre/report-templates"+'/'+template_id,
        beforeSend: function () {
        //   $('.service-info-loader').removeClass('d-none');
        },
        success: function (data) {
            if(data.template){
                editor.setData(data.template.template);
            }else{
                editor.setData(''); 
            }
        },
        error: function (data) {
            console.log('Error:', data);
        }
      });
    });
});

</script>
<script>
    //CK Editor 
    DecoupledEditor
        .create( document.querySelector( '.document-editor__editable' ), {} )
        .then( editor => {
            const toolbarContainer = document.querySelector( '.document-editor__toolbar' );

            toolbarContainer.appendChild( editor.ui.view.toolbar.element );

            window.editor = editor;
            editor.model.document.on( 'change', () => {
                $('#report').val(editor.getData());
            });
        } )
        .catch( err => {
            console.log( err );
        } );
</script>
@endsection