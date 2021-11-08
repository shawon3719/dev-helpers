<?php

namespace Modules\DiagnosticCentre\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Modules\DiagnosticCentre\Entities\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoriesService
{

    public  function __construct()
    {
        //
    }

    public function getTableData(){
        
        $data = Category::orderBy('id', 'desc')->get();

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
                    ->addColumn('parent', function($data){
                        return $data->parent ? $data->parent->name : '';
                    })
                    ->addColumn('action', function($row) use ($user) {
                        
                        $btn = "<div class='btn-group' role='group' aria-label='Basic example'>";

                        $view_url = route('diagnostic-centre.categories.show', $row->id);
                        $edit_url = route('diagnostic-centre.categories.edit', $row->id);
                        $delete_url = route('diagnostic-centre.categories.destroy', $row->id);

                        if ($user->can('diagnostic_categories_show')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-primary' href='$view_url'><i class='far fa-eye'></i></a>";
                        }
                        if ($user->can('diagnostic_categories_edit')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-warning' href='$edit_url'><i class='far fa-edit'></i></a>";
                        }
                        if ($user->can('diagnostic_categories_delete')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-danger delete' data-url='$delete_url' data-title='Are you sure, you want to delete this category?' class='btn btn-xs btn-primary delete' type='button'><i class='fas fa-trash'></i></a>";
                        }

                        $btn."</div>";

                        return $btn;
                    })
                    ->rawColumns(['action','parent'])
                    ->make(true);

        return $tableData;
    }

    public function generateCategoryCode() 
    {
        $setting = Setting::where('key','system_title')->first();
        
        if(!empty($setting)){
            $system_title = $setting->value;
        }

        $prefix = isset($system_title) ? getInitialism($system_title) : getInitialism(env('APP_NAME'));

        $record = Category::orderBy('id','desc')->first();
        
        $expNum = explode($prefix.date('y').'PC', $record->code ?? $prefix.date('y').'PC'.'000000');

        if(!empty($record)){
            $last_prefix = \Str::before($record->code, date('y'));
        }else{
            $last_prefix = "";
        }
        //check first day in a year or prefix has been changed
        if ( date('y-m-d') == date('y').'-01-01' || $prefix != $last_prefix){
            $nextCategoryCode = $prefix.date('y').'PC'.'000001';
        } else {
            //increase 1 with last invoice number
            $nextCategoryCode =  $prefix.date('y').'PC'.sprintf("%06d",$expNum[1]+1);
        }

        return $nextCategoryCode;
    }

}