<?php

namespace Modules\DiagnosticCentre\Services;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\DiagnosticCentre\Entities\Item;
use Yajra\DataTables\Facades\DataTables;

class ItemsService
{

    public  function __construct()
    {
        //
    }


    public function getTableData(){
        
        $data = Item::with('category')->orderBy('id', 'desc')->get();

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
                    ->addColumn('category', function($data){
                        return $data->category ? $data->category->name : '';
                    })
                    ->editColumn('price', function($data){
                        return $data->offer_price != '' ? $data->offer_price : $data->price;
                    })
                    ->editColumn('exp_date', function($data){
                        if(isset($data->exp_date)){
                            $setting = Setting::where('key','date_format')->first();
                            return Carbon::parse($data->exp_date)->format($setting->value ?? 'j F, Y');
                        }
                        return $data->exp_date ? $data->exp_date : '';
                    })

                    ->addColumn('action', function($row) use ($user) {
                        
                        $btn = "<div class='btn-group' role='group' aria-label='Basic example'>";

                        $view_url = route('diagnostic-centre.items.show', $row->id);
                        $edit_url = route('diagnostic-centre.items.edit', $row->id);
                        $delete_url = route('diagnostic-centre.items.destroy', $row->id);

                        if ($user->can('diagnostic_items_show')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-primary' href='$view_url'><i class='far fa-eye'></i></a>";
                        }
                        if ($user->can('diagnostic_items_edit')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-warning' href='$edit_url'><i class='far fa-edit'></i></a>";
                        }
                        if ($user->can('diagnostic_items_delete')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-danger delete' data-url='$delete_url' data-title='Are you sure, you want to delete this item?' class='btn btn-xs btn-primary delete' type='button'><i class='fas fa-trash'></i></a>";
                        }

                        $btn."</div>";

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        return $tableData;
    }

    public  function generateItemCode() 
    {
        $setting = Setting::where('key','system_title')->first();
        
        if(!empty($setting)){
            $system_title = $setting->value;
        }

        $prefix = isset($system_title) ? getInitialism($system_title) : getInitialism(env('APP_NAME'));

        $record = Item::orderBy('id','desc')->first();
        
        $expNum = explode($prefix.date('y').'PI', $record->code ?? $prefix.date('y').'PI'.'000000');

        if(!empty($record)){
            $last_prefix = \Str::before($record->code, date('y'));
        }else{
            $last_prefix = "";
        }
        //check first day in a year or prefix has been changed
        if ( date('y-m-d') == date('y').'-01-01' || $prefix != $last_prefix){
            $nextItemCode = $prefix.date('y').'PI'.'000001';
        } else {
            //increase 1 with last invoice number
            $nextItemCode =  $prefix.date('y').'PI'.sprintf("%06d",$expNum[1]+1);
        }
        
        return $nextItemCode;
    }

}