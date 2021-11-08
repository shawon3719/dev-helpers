## Formatting Data

```
$data['monthly'] =  $monthly = PathologyBilling::selectRaw('MONTHNAME(created_at) as month, sum(total) as total')
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

        
        $data['total'] = array();
        $month_values = "";

        foreach ($monthly as $month) {
            $data['total'][] = $month->total;
            $month_values .= '"' . substr($month->month, 0, 3) . '",';
        }

        $data['months'] = rtrim($month_values, ", ");
        
        return $data;
```

## JS

```

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
```