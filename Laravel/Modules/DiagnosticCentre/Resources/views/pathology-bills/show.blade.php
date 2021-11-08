@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('diagnosticcentre::cruds.pathology-bills.title') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.pathology-bills.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
        <div class="row">
            <div class="hr-text" data-text="Bill Details"></div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label class="" for="code">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.code') }}</label>
                    <p>{{$pathology_bills->code}}</p>
                </div>
            </div>

            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label class="" for="patient_id">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.patient_id') }}</label> 
                    <p>{{$pathology_bills->patient->name}}</p>
                </div>
            </div>

            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label class="" for="referrer_id">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.referrer_id') }}</label>
                    <p>{{$pathology_bills->referrer->name}}</p>
                </div>
            </div>
            <div class="hr-text" data-text="Report Details"></div>
            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="bill_date">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.bill_date') }}</label>
                    <p>{{Carbon\Carbon::parse($pathology_bills->bill_date)->format($settings['date_format'] ?? 'j F, Y')}}</p>
                </div>
            </div>

            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label for="delivery_date">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_date') }}</label>
                    <p>{{Carbon\Carbon::parse($pathology_bills->delivery_date)->format($settings['date_format'] ?? 'j F, Y')}}</p>
                </div>
            </div>

            <div class="form-group col-lg-4 col-md-4 col-sm-12">
                <div class="form-group">
                    <label class="" for="delivery_time">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_time') }}</label>
                    <p>{{Carbon\Carbon::parse($pathology_bills->delivery_time)->format('h:i A')}}</p>
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <div class="form-group">
                    <label class="" for="remarks">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.remarks') }} :</label>
                    <p>{{$pathology_bills->remarks}}</p>
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12 col-sm-12">
                <table class="" style="width: 100%">
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
                        @foreach ($pathology_bills->details as $details)
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
                            <td style="text-align: right; font-weight:bold">{{$pathology_bills->sub_total}}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: bold">Tax</td>
                            <td style="text-align: right; font-weight:bold">{{$pathology_bills->tax}}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: bold">Discount</td>
                            <td style="text-align: right; font-weight:bold">{{$pathology_bills->discount}}</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align: right; font-weight: bold">Net Payable</td>
                            <td style="text-align: right; font-weight:bold">{{$pathology_bills->total}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="form-group">
                <a class="btn-sm btn-info" href="{{ route('diagnostic-centre.pathology-bills.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection