@extends('layouts.admin')
@section('styles')
<style>
    table thead tr th, table tbody tr td {
        padding : 2px 5px!important;
    }
</style>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('diagnosticcentre::cruds.pathology-bills.title') }} Invoice
    </div>
    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.pathology-bills.index') }}">
                    <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                </a>
    
                <a class="btn-sm btn-danger" style="margin-left: 5px" href="{{ route('diagnostic-centre.pathology-bills.pdf', $pathology_bill->id) }}">
                    <i class="far fa-file-pdf"></i> PDF
                </a>
    
                <a class="btn-sm btn-secondary" style="margin-left: 5px" href="{{ route('diagnostic-centre.pathology-bills.print', $pathology_bill->id) }}">
                    <i class="fas fa-print"></i> Print
                </a>
            </div>
    
        </div>
        <ul class="nav nav-tabs bg-light mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="billing_details-tab" data-bs-toggle="tab" href="#billing_details"
                    role="tab" aria-controls="billing_details" aria-selected="true">Invoice</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="payment-tab" data-bs-toggle="tab" href="#payment"
                    role="tab" aria-controls="payment" aria-selected="false">Payment Details</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="billing_details" role="tabpanel"
                aria-labelledby="billing_details-tab">
               
                <div class="card">
                    <div class="card-body" style="border: 1px solid rgb(216 226 238); border-radius: 10px!important">
                        <table style="width: 100%!important">
                            <tr>
                                <td>
                                    <img width="80" src="{{ isset($settings['favicon']) ? Storage::url($settings['favicon']) : asset('img/favicon.png') }}" alt="">
                                </td>
                                <td style="text-align: right">
                                    <p style="font-weight: bold; line-height: .3;">Bill No : {{$pathology_bill->code}}</p>
                                    <small style="line-height: .3;">Payment Status : {{$pathology_bill->payment && $pathology_bill->payment->due_amount > 0 ? 'Due' : 'Paid' }}</small> <br>
                                    <small>Referred By : {{$pathology_bill->referrer->name}} </small><span style="font-size: 8px; text-align: right">({{$pathology_bill->referrer->last_degree}})</span><br>
                                    <small style="line-height: .3; margin-bottom: 0"> Date : {{Carbon\Carbon::now()->format($settings['date_format'] ?? 'j F, Y')}}</small>
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
                                    <small style="line-height: .3;">{{$pathology_bill->patient->name}}</small> <br>
                                    <small style="line-height: .3;">Sex & Age : {{$pathology_bill->patient->sex}} / 
                                        {{Carbon\Carbon::parse(date('Y-m-d', strtotime($pathology_bill->patient->date_of_birth)))->age}} 
                                    </small> <br>
                                    <small style="line-height: .3; margin-bottom: 0"> Date : {{Carbon\Carbon::parse($pathology_bill->bill_date)->format($settings['date_format'] ?? 'j F, Y')}}</small>
                                </td>
                                <td style="text-align: right">
                                    <p style="font-weight: bold; line-height: .3;">From :</p>
                                    <small style="line-height: .3;">{{isset($settings['system_title']) ? $settings['system_title'] : 'Hospital Management System'}}</small> <br>
                                    <small style="line-height: .3;">{{ isset($settings['address']) ? $settings['address'] : 'set-up your address from settings.' }}</small> <br>
                                    <small style="line-height: .3; margin-bottom: 0"> {{ isset($settings['phone']) ? $settings['phone'] : 'set-up your phone.' }}, {{ isset($settings['email']) ? $settings['email'] : 'hms@example.com' }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <br>
        
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 mt-5">
                        <table class="" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.item_id') }}</th>
                                    <th style="text-align: right">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.price') }}</th>
                                    <th style="text-align: right">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.quantity') }}</th>
                                    <th style="text-align: right">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.discount') }} (%)</th>
                                    <th style="text-align: right">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.discount') }}</th>
                                    <th style="text-align: right">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pathology_bill->details as $details)
                                <tr>
                                    <td>{{$details->item->name}}</td>
                                    <td style="text-align: right">{{$details->price}}</td>
                                    <td style="text-align: right">{{$details->quantity}}</td>
                                    <td style="text-align: right">{{$details->discount_percentage}}</td>
                                    <td style="text-align: right">{{$details->discount_amount}}</td>
                                    <td style="text-align: right">{{$details->total}}</td>
                                </tr>
                                @endforeach
                                <tr>
                                   <td colspan="5"></td>
                                   <td></td>
                                </tr>
                                <tr>
                                    <td colspan="5"></td>
                                    <td></td>
                                 </tr>
                                <tr id="total-calculation">
                                    <td colspan="5" style="text-align: right; font-weight: bold">Sub-Total</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->sub_total}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: left;"><small>Total Payable in words :  {{getAmountInWords($pathology_bill->total)}}.</small></td>
                                    <td style="text-align: right; font-weight: bold">Tax</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->tax}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: left"><small>Delivery Date & Time : {{Carbon\Carbon::parse($pathology_bill->delivery_date)->format($settings['date_format'] ?? 'j F, Y').' '.Carbon\Carbon::parse($pathology_bill->delivery_time)->format('h:i A')}}</small></td>
                                    <td style="text-align: right; font-weight: bold">Discount</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->discount}}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" rowspan="3">
                                        <small>Scan the following QR-code to download your report. Or you can visit "<i>{{route('my-reports.index')}}</i>" to view, print or download all your reports.</small>
                                        <br>
                                        <img style="margin-top: 10px!important" width="80" src="{{asset('storage/qr-codes/'.$pathology_bill->code.'.png')}}" alt="">
                                    </td>
                                    <td style="text-align: right; font-weight: bold">Net Payable</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->total}}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-weight: bold">Paid</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->payment->received_amount ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-weight: bold">Due</td>
                                    <td style="text-align: right; font-weight:bold;">{{$pathology_bill->payment->due_amount ?? 0}}</td>
                                </tr>
                            </tbody>
                        </table>
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
                    <div class="form-group">
                        <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.pathology-bills.index') }}">
                            <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                        </a>
        
                        <a class="btn-sm btn-danger" style="margin-left: 5px" href="{{ route('diagnostic-centre.pathology-bills.pdf', $pathology_bill->id) }}">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
        
                        <a class="btn-sm btn-secondary" style="margin-left: 5px" href="{{ route('diagnostic-centre.pathology-bills.print', $pathology_bill->id) }}">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="payment" role="tabpanel"
                aria-labelledby="payment-tab">
                <div class="card">
                    <div class="card-body" style="border: 1px solid rgb(216 226 238); border-radius: 10px!important">
                        <table style="width: 100%!important">
                            <tr>
                                <td>
                                    <img width="80" src="{{ isset($settings['favicon']) ? Storage::url($settings['favicon']) : asset('img/favicon.png') }}" alt="">
                                </td>
                                <td style="text-align: right">
                                    <p style="font-weight: bold; line-height: .3;">Bill No : {{$pathology_bill->code}}</p>
                                    <small style="line-height: .3;">Payment Status : {{$pathology_bill->payment && $pathology_bill->payment->due_amount > 0 ? 'Due' : 'Paid' }}</small> <br>
                                    <small>Referred By : {{$pathology_bill->referrer->name}} </small><span style="font-size: 8px; text-align: right">({{$pathology_bill->referrer->last_degree}})</span><br>
                                    <small style="line-height: .3; margin-bottom: 0"> Date : {{$pathology_bill->bill_date}}</small>
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
                                    <small style="line-height: .3;">{{$pathology_bill->patient->name}}</small> <br>
                                    <small style="line-height: .3;">Sex & Age : {{$pathology_bill->patient->sex}} / 
                                        {{Carbon\Carbon::parse(date('Y-m-d', strtotime($pathology_bill->patient->date_of_birth)))->age}} 
                                    </small> <br>
                                    <small style="line-height: .3; margin-bottom: 0"> Date : {{$pathology_bill->bill_date}}</small>
                                </td>
                                <td style="text-align: right">
                                    <p style="font-weight: bold; line-height: .3;">From :</p>
                                    <small style="line-height: .3;">{{isset($settings['system_title']) ? $settings['system_title'] : 'Hospital Management System'}}</small> <br>
                                    <small style="line-height: .3;">{{ isset($settings['address']) ? $settings['address'] : 'set-up your address from settings.' }}</small> <br>
                                    <small style="line-height: .3; margin-bottom: 0"> {{ isset($settings['phone']) ? $settings['phone'] : 'set-up your phone.' }}, {{ isset($settings['email']) ? $settings['email'] : 'hms@example.com' }}</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <br>
        
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 mt-5">
                        <table class="" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th style="text-align: left">Collect By</th>
                                    <th style="text-align: left">Pay Via</th>
                                    <th style="text-align: left">Paid On</th>
                                    <th style="text-align: right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($payment)
                                    @foreach ($payment->details as $details)
                                        <tr>
                                            <td style="text-align: left">{{$payment->collect_by->name}}</td>
                                            <td style="text-align: left">{{$details->pay_via}}</td>
                                            <td style="text-align: left">{{$details->created_at}}</td>
                                            <td style="text-align: right">{{$details->amount}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <form method="POST" action="{{ route("diagnostic-centre.pathology-bills.update", $pathology_bill->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="payment_from" value="invoice">
                                    <input type="hidden" name="payment_id" value="{{$payment ? $payment->id : ''}}">
                                    <input type="hidden" name="bill_id" value="{{$pathology_bill->id}}">
                                    <tr>
                                        <td  colspan="4"><div class="hr-text" data-text="Add New Payment"></div></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left"><input class="form-control" type="text" readonly name="created_by" value="{{Auth::user()->name}}"></td>
                                        <td style="text-align: left">
                                            <select class="form-control select2 {{ $errors->has('payment_type') ? 'is-invalid' : '' }}" name="payment_type" id="payment_type" style="width: 100%">
                                                <option value="" > Select Payment Mehtod..</option>
                                                @foreach (Config::get('diagnosticcentre.payment_type') as $key=>$type)
                                                    <option value="{{$key}}" > {{$type}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td style="text-align: left"><input class="form-control" type="text" readonly name="created_at" value="{{date('d-m-Y')}}"></td>
                                        <td style="text-align: right"><input class="form-control" type="text" name="amount" value="0"></td>
                                    </tr>
                                    <tr>
                                        <td  colspan="4"><button class="btn btn-sm btn-danger mt-2" type="submit" style="float: right">Save</button></td>
                                    </tr>
                                </form>
                                <tr>
                                    <td  colspan="4"><div class="hr-text" data-text="Summary"></div></td>
                                </tr>
                                <tr>
                                   <td colspan="3"></td>
                                   <td></td>
                                </tr>
                                <tr>
                                    <td colspan="3"></td>
                                    <td></td>
                                 </tr>
                                <tr id="total-calculation">
                                    <td colspan="3" style="text-align: right; font-weight: bold">Sub-Total</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->sub_total ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: left;"><small>In Words : </small></td>
                                    <td style="text-align: right; font-weight: bold">Tax</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->tax ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">Discount</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->discount ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">Net Payable</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->total ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">Paid</td>
                                    <td style="text-align: right; font-weight:bold">{{$pathology_bill->payment->received_amount ?? 0}}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: right; font-weight: bold">Due</td>
                                    <td style="text-align: right; font-weight:bold;">{{$pathology_bill->payment->due_amount ?? 0}}</td>
                                </tr>
                            </tbody>
                        </table>
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
                    <div class="form-group">
                        <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.pathology-bills.index') }}">
                            <i class="fas fa-arrow-left"></i> {{ trans('global.back_to_list') }}
                        </a>
        
                        <a class="btn-sm btn-danger" style="margin-left: 5px" href="{{ route('diagnostic-centre.pathology-bills.pdf', $pathology_bill->id) }}">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
        
                        <a class="btn-sm btn-secondary" style="margin-left: 5px" href="{{ route('diagnostic-centre.pathology-bills.print', $pathology_bill->id) }}">
                            <i class="fas fa-print"></i> Print
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



@endsection