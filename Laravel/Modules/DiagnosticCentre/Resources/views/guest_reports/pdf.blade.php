<style>
    table{
        width: 100%!important;
    }
    figure table thead  tr th, figure table tbody tr td{
        border: 1px solid #dedede!important;
        padding: 5px 15px!important;
    }
</style>
<div class="card">
    <div class="card">
        <div class="card-body" style="border: 1px solid #d8e2ee; border-radius: 10px!important; ">
            <table style="width: 100%!important">
                <tr >
                    <td style="padding: 5px ">
                        <img width="80" src="data:image/png;base64,{{ base64_encode(file_get_contents(isset($settings['favicon']) ? 'storage/'.$settings['favicon'] : 'img/favicon.png'))}}">
                    </td>
                    <td style="text-align: right; padding: 5px ">
                        <small style="font-weight: bold; ">Bill No : {{$pathology_report->bill->code}}</small><br>
                        <small>Payment Status : Paid</small><br>
                        <small>Referred By : {{$pathology_report->bill->referrer->name}} </small><span style="font-size: 8px; text-align: right">({{$pathology_report->bill->referrer->last_degree}})</span><br>
                        <small style=" margin-bottom: "> Date : {{$pathology_report->bill->bill_date}}</small>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top: 20px!important">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <table style="width: 100%!important mt-5" >
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
                        <img width="100" style="line-height: 0!important"  src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/signature.png')))}}" alt="">
                        <p style="border-top: 1px solid #9e9e9e; text-align: center">Authorised By</p>
                    </td> --}}
                </tr>
            </table>
        </div>
    </div>
</div>
</div>