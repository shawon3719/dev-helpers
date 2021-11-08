<?php

namespace Modules\DiagnosticCentre\Services;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PathologyReport;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class pathologyReportsService
{

    public  function __construct()
    {
        //
    }

    public function getTableData(){
        
        $data = PathologyReport::with('bill')->orderBy('id', 'desc')->get();

        $user = Auth::user();

        $tableData = DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowAttr([
                        'data-entry-id' => function($row) {
                            return $row->id;
                        },
                    ])
                    ->addColumn('select', function(){
                        return  '';
                    })
                    ->editColumn('bill', function($data){
                        return $data->bill->code ?? '';
                    })
                    ->editColumn('patient', function($data){
                        return $data->bill->patient->name ?? '';
                    })
                    ->editColumn('delivery_date', function($data){
                        if(isset($data->bill->delivery_date)){
                            $setting = Setting::where('key','date_format')->first();
                            return Carbon::parse($data->bill->delivery_date)->format($setting->value ?? 'j F, Y');
                        }
                        return $data->bill->delivery_date ? $data->bill->delivery_date : '';
                    })
                    ->addColumn('action', function($row) use ($user) {
                        
                        $btn = "<div class='btn-group' role='group' aria-label='Basic example'>";

                        $view_url = route('diagnostic-centre.pathology-reports.show', $row->id);
                        $edit_url = route('diagnostic-centre.pathology-reports.edit', $row->id);
                        $delete_url = route('diagnostic-centre.pathology-reports.destroy', $row->id);

                        if ($user->can('pathology_reports_show')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-primary' href='$view_url'><i class='far fa-eye'></i></a>";
                        }
                        if ($user->can('pathology_reports_edit')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-warning' href='$edit_url'><i class='far fa-edit'></i></a>";
                        }
                        if ($user->can('pathology_reports_delete')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-danger delete' data-url='$delete_url' data-title='Are you sure, you want to delete this bill?' class='btn btn-xs btn-primary delete' type='button'><i class='fas fa-trash'></i></a>";
                        }

                        $btn."</div>";

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        return $tableData;
    }
    

    public function getCreateTableData(){
        
        $data = PathologyBilling::with('details', 'patient', 'referrer')->orderBy('delivery_date', 'desc')->orderBy('delivery_time', 'desc')->get();

        $user = Auth::user();

        $tableData = DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowAttr([
                        'data-entry-id' => function($row) {
                            return $row->id;
                        },
                    ])
                    ->addColumn('select', function(){
                        return  '';
                    })
                    ->editColumn('patient', function($data){
                        return $data->patient->name ?? '';
                    })
                    ->editColumn('referrer', function($data){
                        return $data->referrer->name ?? '';
                    })
                    ->editColumn('delivery_date', function($data){
                        if(isset($data->delivery_date)){
                            $setting = Setting::where('key','date_format')->first();
                            return Carbon::parse($data->delivery_date)->format($setting->value ?? 'j F, Y');
                        }
                        return $data->delivery_date ? $data->delivery_date : '';
                    })
                    ->editColumn('bill_date', function($data){
                        if(isset($data->bill_date)){
                            $setting = Setting::where('key','date_format')->first();
                            return Carbon::parse($data->bill_date)->format($setting->value ?? 'j F, Y');
                        }
                        return $data->bill_date ? $data->bill_date : '';
                    })
                    ->addColumn('action', function($row) use ($user) {
                        
                        $btn = "<div class='btn-group' role='group' aria-label='Basic example'>";

                        $create_url = route('diagnostic-centre.pathology-reports.create.report', $row->id);

                        if ($user->can('pathology_reports_create')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-primary' href='$create_url'><i class='fas fa-vials'></i></a>";
                        }

                        $btn."</div>";

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        return $tableData;
    }

    public  function generatepathologyReportsCode() 
    {
        $setting = Setting::where('key','system_title')->first();
        
        if(!empty($setting)){
            $system_title = $setting->value;
        }

        $prefix = isset($system_title) ? getInitialism($system_title) : getInitialism(env('APP_NAME'));

        $record = PathologyReport::orderBy('id','desc')->first();
        
        $expNum = explode($prefix.date('y').'PR', $record->code ?? $prefix.date('y').'PR'.'000000');

        if(!empty($record)){
            $last_prefix = \Str::before($record->code, date('y'));
        }else{
            $last_prefix = "";
        }
        //check first day in a year
        if ( date('y-m-d') == date('y').'-01-01' || $prefix != $last_prefix){
            $nextReportTemplateCode = $prefix.date('y').'PR'.'000001';
        } else {
            //increase 1 with last invoice number
            $nextReportTemplateCode =  $prefix.date('y').'PR'.sprintf("%06d",$expNum[1]+1);
        }
        
        return $nextReportTemplateCode;
    }

}