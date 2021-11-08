<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class EmployeesService
{

    public  function __construct()
    {
        //
    }

    public function getTableData(){
        $data = Employee::latest()->orderBy('id', 'desc')->get();

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
                    ->addColumn('action', function($row) use ($user) {
                        
                        $btn = "<div class='btn-group' role='group' aria-label='Basic example'>";

                        $view_url = route('admin.employees.show', $row->id);
                        $edit_url = route('admin.employees.edit', $row->id);
                        $delete_url = route('admin.employees.destroy', $row->id);

                        if ($user->can('employees_show')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-primary' href='$view_url'><i class='far fa-eye'></i></a>";
                        }
                        if ($user->can('employees_edit')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-warning' href='$edit_url'><i class='far fa-edit'></i></a>";
                        }
                        if ($user->can('employees_delete')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-danger delete' data-url='$delete_url' data-title='Are you sure, you want to delete this employee?' class='btn btn-xs btn-primary delete' type='button'><i class='fas fa-trash'></i></a>";
                        }

                        $btn."</div>";

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

        return $tableData;
    }


    public  function generateEmployeeCode() 
    {
        $setting = Setting::where('key','system_title')->first();
        
        if(!empty($setting)){
            $system_title = $setting->value;
        }

        $prefix = isset($system_title) ? getInitialism($system_title) : getInitialism(env('APP_NAME'));

        $record = Employee::orderBy('id','desc')->first();
        
        $expNum = explode($prefix.date('y').'EM', $record->code ?? $prefix.date('y').'EM'.'000000');

        if(!empty($record)){
            $last_prefix = \Str::before($record->code, date('y'));
        }else{
            $last_prefix = "";
        }
        //check first day in a year or prefix has been changed
        if ( date('y-m-d') == date('y').'-01-01' || $prefix != $last_prefix){
            $nextEmployeeCode = $prefix.date('y').'EM'.'000001';
        } else {
            //increase 1 with last invoice number
            $nextEmployeeCode =  $prefix.date('y').'EM'.sprintf("%06d",$expNum[1]+1);
        }

        
        return $nextEmployeeCode;
    }

}