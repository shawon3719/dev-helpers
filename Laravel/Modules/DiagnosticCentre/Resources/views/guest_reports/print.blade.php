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
        table{
            width: 100%!important;
        }
        figure table thead  tr th, figure table tbody tr td{
            border: 1px solid #dedede!important;
            padding: 5px 15px!important;
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
                        <small style="font-weight: bold; ">Bill No : {{$pathology_report->bill->code}}</small><br>
                        <small>Payment Status : Due</small><br>
                        <small>Referred By : {{$pathology_report->bill->referrer->name}} </small><span style="font-size: 8px; text-align: right">({{$pathology_report->bill->referrer->last_degree}})</span><br>
                        <small style=" margin-bottom: "> Date : {{$pathology_report->bill->bill_date}}</small>
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
                        <small>{{$pathology_report->bill->patient->name}}</small> <br>
                        <small>Sex & Age : {{$pathology_report->bill->patient->sex}} / 
                            {{Carbon\Carbon::parse(date('Y-m-d', strtotime($pathology_report->bill->patient->date_of_birth)))->age}} 
                        </small> <br>
                        <small style=" margin-bottom: 0"> Date : {{$pathology_report->bill->bill_date}}</small>
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
            @php
                echo($pathology_report->report)
            @endphp
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 mt-5 mb-5" style="margin-top: 100px!important">
            <table style="width: 100%!important" class="mt-5">
                <tr>
                    <td style="text-align: right">
                        <p>Prepared By : {{ isset($settings['system_title']) ? $settings['system_title'] : env('APP_NAME') }}</p>
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