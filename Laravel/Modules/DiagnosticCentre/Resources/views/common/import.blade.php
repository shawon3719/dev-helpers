@extends('layouts.admin')
@section('content')
    <section class="simple-validation">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Import {{$title}}</h5>
                        <div class="heading-elements">
                            <a href="{{ url()->previous() }}" class="btn-sm btn-light mr-1 mb-1">
                                <i class="fas fa-arrow-left"></i> Back to list
                            </a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                        <form class="importForm" action="{{$action_url}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="alert alert-light-primary color-primary mb-2" role="alert">
                                Please find this <a href="{{asset($filename)}}">sample format</a>. Keep the header row and put the data from 2nd row.
                            </div>
                            <label for="document" class="mb-2">Upload a <code>.xls</code> or <code>.xlsx</code> file.</label>                            
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12">
                                    <input type="file" name="document" class="form-control document">
                                    @if($errors->has('document'))
                                        <span class="text-danger">{{ $errors->first('document') }}</span>
                                    @endif
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            {{-- <button type="submit" class="btn btn-primary mr-1 mb-1"><i class="bx bxs-save"></i> Upload</button> --}}
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection






