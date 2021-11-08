<?php

namespace Modules\DiagnosticCentre\Services;

use App\Models\Patient;
use Carbon\Carbon;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PaymentDetails;

class DiagnosticService
{

    public  function __construct()
    {
        //
    }



    public  function getIndexItem() 
    {
        $data['patients_today'] = Patient::whereDate('created_at', Carbon::today())->count();
        $data['bills_today'] = PathologyBilling::select(\DB::raw('sum(total) as total'))->whereDate('created_at', Carbon::today())->get();
        $data['collections_today'] = PaymentDetails::select(\DB::raw('sum(amount) as total'))->whereDate('created_at', Carbon::today())->get();
        $data['pending_reports_today'] = PathologyBilling::whereDate('delivery_date', Carbon::today())->count();

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

        $data['payments'] = PathologyBilling::leftJoin('payments', 'payments.payment_reference_id', 'pathology_billings.id')
        ->where('payments.payment_reference', 'PathologyBilling')
        ->select(\DB::raw('
            count(case when due_amount <= 0 then 1 else null end) as paid,
            count(case when due_amount > 0 then 1 else null end) as due
        '))
        ->get();
        
        return $data;
    }

}