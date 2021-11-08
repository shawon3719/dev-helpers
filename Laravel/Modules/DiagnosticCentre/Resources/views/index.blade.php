@extends('diagnosticcentre::layouts.master')
@section('styles')
<style>
.stats-icon svg{
    color: #fff!important;
    font-size: 1.7rem;
    speak: never;
    font-style: normal;
    font-weight: normal;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

</style>
@endsection
@section('content')

<div class="page-content">
    @if(Auth::user()->can('dashboard_data_access'))
        <section class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="fas fa-wheelchair"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Patients Today</h6>
                                        <h6 class="font-extrabold mb-0">{{$patients_today ?? 0}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Bills Today</h6>
                                        <h6 class="font-extrabold mb-0">{{$settings['currency_symbol'] ?? '$'}} {{$bills_today[0]['total'] ?? 0}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Collections</h6>
                                        <h6 class="font-extrabold mb-0">{{$settings['currency_symbol'] ?? '$'}} {{$collections_today[0]['total'] ?? 0}}</h6>
                                        {{-- <small>Today</small> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6 col-sm-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="fas fa-hourglass-half"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Pending Report</h6>
                                        <h6 class="font-extrabold mb-0">{{$pending_reports_today ?? 0}}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9 col-lg-9 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Annual Pathology Sales</h4>
                            </div>
                            <div class="card-body">
                                <div id="annual-pathology-sales"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 col-lg-3 col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Payment Status</h4>
                            </div>
                            <div class="card-body">
                                <div id="pathology-payment-status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- <section class="section">
            <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h4>Line Area Chart</h4>
                        </div>
                        <div class="card-body">
                            <div id="area"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h4>Radial Gradient Chart</h4>
                        </div>
                        <div class="card-body">
                            <div id="radialGradient"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Line Chart</h4>
                        </div>
                        <div class="card-body">
                            <div id="line"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bar Chart</h4>
                        </div>
                        <div class="card-body">
                            <div id="bar"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Radial Gradient Chart</h4>
                        </div>
                        <div class="card-body">
                            <div id="candle"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section> --}}
    @else
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5>You're logged-in!</h5>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>
@endsection
@section('scripts')
<script>



var optionsProfileVisit = {
	annotations: {
		position: 'back'
	},
	dataLabels: {
		enabled:false
	},
	chart: {
		type: 'bar',
		height: 300
	},
	fill: {
		opacity:1
	},
	plotOptions: {
	},
	series: [{
		name: 'Pathology sales',
		data: [{!! implode(",", $total) !!}]
	}],
	colors: '#435ebe',
	xaxis: {
		categories: [{!! $months !!}],
	},
}

let optionsVisitorsProfile  = {
	series: [{!! $payments[0]->paid !!}, {!! $payments[0]->due !!}],
	labels: ['Paid', 'Due'],
	colors: ['#5ddab4','#ff7976'],
	chart: {
		type: 'donut',
		width: '100%',
		height:'350px'
	},
	legend: {
		position: 'bottom'
	},
	plotOptions: {
		pie: {
			donut: {
				size: '30%'
			}
		}
	}
}

var chartVisitorsProfile = new ApexCharts(document.getElementById('pathology-payment-status'), optionsVisitorsProfile);
var chartProfileVisit = new ApexCharts(document.querySelector("#annual-pathology-sales"), optionsProfileVisit);

chartProfileVisit.render();
chartVisitorsProfile.render()

</script>
@endsection