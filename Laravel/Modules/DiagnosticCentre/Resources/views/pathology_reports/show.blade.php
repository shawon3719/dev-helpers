@extends('layouts.admin')
@section('styles')
<style>
    table{
        width: 100%!important;
    }
    figure table thead  tr th, figure table tbody tr td{
        border: 1px solid #dedede!important;
        padding: 5px 15px!important;
    }
</style>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('diagnosticcentre::cruds.pathology-report.title') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            @if(Storage::disk('public')->exists($pathology_report->report))
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.pathology-reports.index') }}">
                    <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                </a>
            @else
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.pathology-reports.index') }}">
                    <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                </a>

                <a class="btn-sm btn-danger" style="margin-left: 5px" href="{{ route('diagnostic-centre.pathology-reports.pdf', $pathology_report->id) }}">
                    <i class="far fa-file-pdf"></i> PDF
                </a>

                <a class="btn-sm btn-secondary" style="margin-left: 5px" href="{{  route('diagnostic-centre.pathology-reports.print', $pathology_report->id) }}">
                    <i class="fas fa-print"></i> Print
                </a> 
            @endif
        </div>

        @if(Storage::disk('public')->exists($pathology_report->report))
            <div class="card">
                <div class="card-body">
                    <p style="float: right">Click <i class="fas fa-download"></i> to download, or <i class="fas fa-print"></i> to print.</p>
                    <embed
                        src="{{ Storage::url($pathology_report->report) }}"
                        style="width:100%; height:800px;"
                        frameborder="0">
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body" style="border: 1px solid rgb(216 226 238); border-radius: 10px!important">
                    <table style="width: 100%!important">
                        <tr>
                            <td>
                                <img width="80" src="{{ isset($settings['favicon']) ? Storage::url($settings['favicon']) : asset('img/favicon.png') }}" alt="">
                            </td>
                            <td style="text-align: right">
                                <p style="font-weight: bold; line-height: .3;">Report No. : {{$pathology_report->code}}</p>
                                <small style="line-height: .3;">Payment Status : {{$pathology_report->bill->payment && $pathology_report->bill->payment->due_amount > 0 ? 'Due' : 'Paid' }}</small> <br>
                                <small>Referred By : {{$pathology_report->bill->referrer->name}} </small><span style="font-size: 8px; text-align: right">({{$pathology_report->bill->referrer->last_degree}})</span><br>
                                <small style="line-height: .3; margin-bottom: 0"> Date : {{$pathology_report->reportinh_date}}</small>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <table style="width: 100%!important">
                        <tr>
                            <td style="text-align: left">
                                <p style="font-weight: bold; line-height: .3;">Patient</p>
                                <small style="line-height: .3;">{{$pathology_report->bill->patient->name}}</small> <br>
                                <small style="line-height: .3;">Sex & Age : {{$pathology_report->bill->patient->sex}} / 
                                    {{Carbon\Carbon::parse(date('Y-m-d', strtotime($pathology_report->bill->patient->date_of_birth)))->age}} 
                                </small> <br>
                                <small style="line-height: .3; margin-bottom: 0"> Reporting Date : {{$pathology_report->reporting_date}}</small>
                            </td>
                            <td style="text-align: right">
                                <p style="font-weight: bold; line-height: .3;">From :</p>
                                <small style="line-height: .3;">{{isset($settings['system_title']) ? $settings['system_title'] : ''}}</small> <br>
                                <small style="line-height: .3;">4788 Columbia Boulevard Baltimore, MD 21229</small> <br>
                                <small style="line-height: .3; margin-bottom: 0"> +1410-395-3067, ibn-sina@example.com</small>
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <br>

                <div class="form-group col-lg-12 col-md-12 col-sm-12 mt-5">
                        @php
                        echo($pathology_report->report);
                        @endphp
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-5 mb-5">
                    <table style="width: 100%!important" class="mt-5">
                        <tr>
                            <td style="text-align: right">
                                <p>Prepared By : {{Auth::user()->name}} ({{Auth::user()->roles->first()->title}}).</p>
                            </td>
                            {{-- <td style="text-align: center!important" width="20%">
                                <img width="100"  src="{{asset('img/signature.png')}}" alt="">
                                <p style="border-top: 1px solid #9e9e9e; text-align: center">Authorised By</p>
                            </td> --}}
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>



@endsection