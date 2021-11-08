<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($settings['system_title']) ? $settings['system_title'] : env('APP_NAME') }}</title>
    @include('partials.links')
    <style>
        @media screen {
            div.divFooter {
                display: block;
            }
        }
        @media print {
            div.divFooter {
                position: fixed;
                bottom: 0;
            }
        }
        .main-body{
            padding: 20px 10px!important;
        }
        table thead tr th, table tbody tr td {
            padding : 2px 5px!important;
        }
    </style>
</head>
<body onload="window.print()">
<div class="container">
<div class="card main-body">
    <div class="card">
        <div class="card-body" style="border: 1px solid rgb(216 226 238); border-radius: 10px!important; ">
            <table style="width: 100%!important">
                <tr >
                    <td style="padding: 5px ">
                        <img width="80" src="{{asset(isset($settings['favicon']) ? Storage::url($settings['favicon']) : 'img/favicon.png') }}" alt="">
                    </td>
                    <td style="text-align: right; padding: 5px ">
                        <small style="font-weight: bold; ">Bill No : {{$pathology_bills->code}}</small><br>
                        <small>Payment Status : {{$pathology_bills->payment && $pathology_bills->payment->due_amount > 0 ? 'Due' : 'Paid' }}</small><br>
                        <small>Referred By : {{$pathology_bills->referrer->name}} </small><span style="font-size: 8px; text-align: right">({{$pathology_bills->referrer->last_degree}})</span><br>
                        <small style=" margin-bottom: "> Date : {{Carbon\Carbon::now()->format($settings['date_format'] ?? 'j F, Y')}}</small>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top: 20px!important">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <table style="width: 100%!important" >
                <tr>
                    <td style="text-align: left">
                        <small style="font-weight: bold; ">Patient</small> <br>
                        <small>{{$pathology_bills->patient->name}}</small> <br>
                        <small>Sex & Age : {{$pathology_bills->patient->sex}} / 
                            {{Carbon\Carbon::parse(date('Y-m-d', strtotime($pathology_bills->patient->date_of_birth)))->age}} 
                        </small> <br>
                        <small style=" margin-bottom: 0"> Date : {{Carbon\Carbon::parse($pathology_bills->bill_date)->format($settings['date_format'] ?? 'j F, Y')}}</small>
                    </td>
                    <td style="text-align: right">
                        <small style="font-weight: bold; ">From :</small>
                        <small style="">{{isset($settings['system_title']) ? $settings['system_title'] : 'Hospital Management System'}}</small> <br>
                        <small style="">{{ isset($settings['address']) ? $settings['address'] : 'set-up your address from settings.' }}</small> <br>
                        <small style=" margin-bottom: 0"> {{ isset($settings['phone']) ? $settings['phone'] : 'set-up your phone.' }}, {{ isset($settings['email']) ? $settings['email'] : 'hms@example.com' }}</small>
                    </td>
                </tr>
            </table>
        </div>
        <br>
        <br>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 mt-5">
            <table class="" style="width: 100%">
                <thead>
                    <tr>
                        <th style="text-align: left">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.item_id') }}</th>
                        <th style="text-align: right">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.price') }}</th>
                        <th style="text-align: right">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.quantity') }}</th>
                        <th style="text-align: right; white-space: nowrap">{{ trans('diagnosticcentre::cruds.pathology-bills.fields.discount') }} (%)</th>
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
                    <tr id="total-calculation" style="margin-top: 30px!important">
                        <td colspan="5" style="text-align: right; font-weight: bold">Sub-Total</td>
                        <td style="text-align: right; font-weight:bold">{{$pathology_bills->sub_total}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left;"><small>Total Payable in words :  {{getAmountInWords($pathology_bills->total)}}.</small></td>
                            <td colspan="2" style="text-align: right; font-weight: bold">Tax</td>
                        <td style="text-align: right; font-weight:bold">{{$pathology_bills->tax}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: left"><small>Delivery Date & Time : {{Carbon\Carbon::parse($pathology_bills->delivery_date)->format($settings['date_format'] ?? 'j F, Y').' '.Carbon\Carbon::parse($pathology_bills->delivery_time)->format('h:i A')}}</small></td>
                        <td colspan="2" style="text-align: right; font-weight: bold">Discount</td>
                        <td style="text-align: right; font-weight:bold">{{$pathology_bills->discount}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" rowspan="3" style="margin-top: 7px!important">
                            <small>Scan the following QR-code to download your report. Or you can visit "<i>{{route('my-reports.index')}}</i>" to view, print or download all your reports.</small>
                            <br>
                            <img style="margin-top: 10px!important" width="80" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/qr-codes/'.$pathology_bills->code.'.png')))}}">
                        </td>
                        <td colspan="2"  style="text-align: right; font-weight: bold">Net Payable</td>
                        <td style="text-align: right; font-weight:bold">{{$pathology_bills->total}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"  style="text-align: right; font-weight: bold">Paid</td>
                        <td style="text-align: right; font-weight:bold">{{$pathology_bills->payment->received_amount ?? 0}}</td>
                    </tr>
                    <tr>
                        <td colspan="2"  style="text-align: right; font-weight: bold">Due</td>
                        <td style="text-align: right; font-weight:bold;">{{$pathology_bills->payment->due_amount ?? 0}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 mt-5 mb-5" style="margin-top: 100px!important">
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
</div>
</div>
</div>
@include('partials.scripts')
<script type="text/javascript">
    window.onload = function() { window.print(); }
</script>
</body>

</html>