@extends('diagnosticcentre::layouts.master')
@section('styles')
<style>
    label {
        width: 100%!important;
    }
    .input-group-text {
        padding: .775rem .75rem!important;
    }
</style>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('diagnosticcentre::cruds.pathology-bills.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("diagnostic-centre.pathology-bills.update", $pathology_bills->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="hr-text" data-text="Bill Details"></div>
                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="code">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.code') }}</label>
                        <input class="form-control {{ $errors->has('code') ? 'is-invalid' : '' }}" readonly type="text" name="code" id="code" value="{{ old('code', $pathology_bills->code) }}" required>
                        @if($errors->has('code'))
                            <span class="text-danger">{{ $errors->first('code') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.code_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="patient_id">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.patient_id') }} <a style="float: right!important" type="button" class="add-patient text-success">+add new</a></label> 
                        <select class="form-control select2 {{ $errors->has('patient_id') ? 'is-invalid' : '' }}" name="patient_id" id="patient_id" style="width: 100%">
                            <option value="" > Select patient..</option>
                            @foreach ($patients as $patient)
                                <option value="{{$patient->id}}" {{$pathology_bills->patient_id == $patient->id ? 'selected' : ''}}>{{$patient->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('patient_id'))
                            <span class="text-danger">{{ $errors->first('patient_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.patient_id_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="referrer_id">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.referrer_id') }} <a style="float: right!important; color:#198754" type="button" class="add-referrer">+add new</a></label>
                        <select class="form-control select2 {{ $errors->has('referrer_id') ? 'is-invalid' : '' }}" name="referrer_id" id="referrer_id" style="width: 100%">
                            <option value="" > Select Referrer..</option>
                            @foreach ($referrers as $referrer)
                                <option value="{{$referrer->id}}" {{$pathology_bills->referrer_id == $referrer->id ? 'selected' : ''}}>{{$referrer->name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('referrer_id'))
                            <span class="text-danger">{{ $errors->first('referrer_id') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.referrer_id_helper') }}</span>
                    </div>
                </div>
                <div class="hr-text" data-text="Report Details"></div>
                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="bill_date">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.bill_date') }}</label>
                        <div class="input-group date" id="bill_date">
                            <input type="text" name="bill_date" value="{{ $pathology_bills->bill_date }}" auto-complete="off" class="datepicker-here form-control digits" required="" placeholder="{{ $pathology_bills->bill_date }}">
                            <div class="input-group-addon input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('bill_date'))
                            <span class="text-danger">{{ $errors->first('bill_date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.bill_date_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="delivery_date">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_date') }}</label>
                        <div class="input-group date" id="delivery_date">
                            <input type="text" name="delivery_date" value="{{ $pathology_bills->delivery_date }}" placeholder="{{ $pathology_bills->bill_date }}"  auto-complete="off" class="datepicker-here form-control digits" required="">
                            <div class="input-group-addon input-group-append">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('delivery_date'))
                            <span class="text-danger">{{ $errors->first('delivery_date') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_date_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="delivery_time">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_time') }}</label>
                        <div class="input-group date" id="delivery_time">
                            <input type="text" name="delivery_time" value="{{ $pathology_bills->delivery_time }}" class="form-control" title="" required="" id="id_delivery_time">
                            <div class="input-group-addon input-group-append">
                                <div class="input-group-text">
                                    <i class="far fa-clock"></i>
                                </div>
                            </div>
                        </div>
                        @if($errors->has('delivery_time'))
                            <span class="text-danger">{{ $errors->first('delivery_time') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.delivery_time_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="" for="remarks">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.remarks') }}</label>
                        <textarea class="form-control {{ $errors->has('remarks') ? 'is-invalid' : '' }}" type="text" name="remarks" id="remarks" value="{{ old('remarks', $pathology_bills->remarks) }}"></textarea>
                        @if($errors->has('remarks'))
                            <span class="text-danger">{{ $errors->first('remarks') }}</span>
                        @endif
                        <span class="help-block">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.remarks_helper') }}</span>
                    </div>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="item_id">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.item_id') }} <a style="float: right!important;" class="add-new-item text-success" type="button">+add new</a></label>
                        <select class="form-control select2 item-select {{ $errors->has('item_id') ? 'is-invalid' : '' }}" name="item_id" id="item_id" style="width: 100%">
                            <option value="" > Select item..</option>
                            @foreach ($items as $item)
                                <option value="{{$item->id}}" >{{$item->name}} [{{$item->category->name}}]</option>
                            @endforeach
                        </select>
                    </div>
                    <table class="table bills-table" style="width: 100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.item_id') }}</th>
                                <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.price') }}</th>
                                <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.quantity') }}</th>
                                <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.discount') }} (%)</th>
                                <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.discount') }}</th>
                                <th>{{ trans('diagnosticcentre::cruds.pathology-bills.fields.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pathology_bills->details as $details)
                            <tr>
                                <td><a type="button" class="remove btn btn-sm btn-danger"><i class="fas fa-times"></i></a></td>
                                <td><input type="hidden" placeholder="item" name="item_id[]" value={{$details->item->id}} class="form-control price">
                                <input readonly type="text" placeholder="item" value={{$details->item->name}} class="form-control price"></td>
                                <td><input readonly type="text" placeholder="price" name="price[]" value={{$details->price}} class="item_price price form-control"></td>
                                <td><input type="text" placeholder="quantity" name="quantity[]" value={{$details->quantity}} class="quantity form-control"></td>
                                <td><input type="text" placeholder="discount %" name="discount_percentage[]" value={{$details->discount_percentage}} class="discount-percentage form-control"></td>
                                <td><input readonly type="text" placeholder="discount amt." name="discount_amount[]" value={{$details->discount_amount}} class="discount-amount form-control"></td>
                                <td><input readonly type="text" placeholder="total" name="total[]" value={{($details->price*$details->quantity)-$details->discount_amount}} class="total-amount form-control"></td>
                            </tr>
                            @endforeach
                            <tr id="total-calculation">
                                <td colspan="6" style="text-align: right; font-weight: bold">Sub-Total</td>
                                <input type="hidden" name="sub_total" class="sub-total" value="{{$pathology_bills->sub_total}}">
                                <td class="sub-total-text" style="text-align: right;">{{$pathology_bills->sub_total}}</td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: right; font-weight: bold">Tax</td>
                                <td><input type="text" name="tax" class="form-control tax" value="{{$pathology_bills->tax}}" style="text-align: right;"></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: right; font-weight: bold">Discount</td>
                                <td ><input type="text" class="form-control discount" name="discount" style="text-align: right;" value="{{$pathology_bills->discount}}"></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: right; font-weight: bold">Paid</td>
                                <td style="text-align: right;">
                                    @if($pathology_bills->payment)
                                        {{$pathology_bills->payment->received_amount ?? 0}}
                                        <input type="hidden" class="form-control paid-amount" name="paid" style="text-align: right;" value="{{$pathology_bills->payment->received_amount}}">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: right; font-weight: bold">Net Payable</td>
                                <input type="hidden" name="net_payable" class="net-payable" value="{{$pathology_bills->total}}">
                                <td class="net-payable-text" style="text-align: right;">{{$pathology_bills->total}}</td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: right; font-weight: bold">Received Amount</td>
                                <td ><input type="text" class="form-control received_amount" name="received_amount" style="text-align: right;" value="{{$pathology_bills->payment->received_amount ?? 0}}"></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align: right; font-weight: bold">Pay Via</td>
                                <td >
                                    <select class="form-control select2 {{ $errors->has('payment_type') ? 'is-invalid' : '' }}" name="payment_type" id="payment_type" style="width: 100%">
                                        <option value="" > Select Payment Method..</option>
                                        @foreach (Config::get('diagnosticcentre.payment_type') as $key=>$type)
                                            <option value="{{$key}}" @if(!empty($pathology_bills->payment)) {{$pathology_bills->payment->details[0]->pay_via == $key ? 'selected' : ""}} @endif> {{$type}}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
@include('diagnosticcentre::pathology-bills.js')
@endsection