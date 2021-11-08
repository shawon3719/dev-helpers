<?php

namespace Modules\DiagnosticCentre\Services;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\DiagnosticCentre\Entities\Item;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PathologyBillingDetails;
use Modules\DiagnosticCentre\Entities\Payment;
use Modules\DiagnosticCentre\Entities\PaymentDetails;
use Yajra\DataTables\Facades\DataTables;

class PathologyBillsService
{

    public $paginatedList = true;
    
    public  function __construct()
    {
        //
    }

    public function getTableData(){
        
        $data = PathologyBilling::with('payment','patient')->orderBy('id', 'desc')->get();

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
                    ->addColumn('patient', function($data){
                        return $data->patient ? $data->patient->name : '';
                    })
                    ->editColumn('payment_status', function($data){
                        return $data->payment && $data->payment->due_amount > 0 ? "<span class='badge bg-danger'>Due</span>" : "<span class='badge bg-success'>Paid</span>";
                    })
                    ->editColumn('paid', function($data){
                        return $data->payment &&  $data->payment->received_amount ? $data->payment->received_amount :  0 ;
                    })
                    ->editColumn('bill_date', function($data){
                        if(isset($data->bill_date)){
                            $setting = Setting::where('key','date_format')->first();
                            return Carbon::parse($data->bill_date)->format($setting->value ?? 'j F, Y');
                        }
                        return $data->bill_date ? $data->bill_date : '';
                    })
                    ->editColumn('delivery_date', function($data){
                        if(isset($data->delivery_date)){
                            $setting = Setting::where('key','date_format')->first();
                            return Carbon::parse($data->delivery_date)->format($setting->value ?? 'j F, Y');
                        }
                        return $data->delivery_date ? $data->delivery_date : '';
                    })
                    ->editColumn('due_amount', function($data){
                        return $data->payment &&  $data->payment->due_amount ? $data->payment->due_amount : 0 ;
                    })
                    ->addColumn('action', function($row) use ($user) {
                        
                        $btn = "<div class='btn-group' role='group' aria-label='Basic example'>";

                        $create_url = route('diagnostic-centre.pathology-reports.create.report', $row->id);
                        $view_url = route('diagnostic-centre.pathology-bills.show', $row->id);
                        $edit_url = route('diagnostic-centre.pathology-bills.edit', $row->id);
                        $delete_url = route('diagnostic-centre.pathology-bills.destroy', $row->id);
                        $invoice_url = route('diagnostic-centre.pathology-bills.invoice', $row->id);

                        if ($user->can('diagnostic_items_show')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-primary' href='$view_url'><i class='far fa-eye'></i></a>";
                        }
                        if ($user->can('diagnostic_pathology_bills_invoice_create')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-success' href='$invoice_url'><i class='fas fa-file-invoice'></i></a>";
                        }
                        if ($user->can('pathology_reports_create')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-primary' href='$create_url'><i class='fas fa-vials'></i></a>";
                        }
                        if ($user->can('diagnostic_items_edit')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-warning' href='$edit_url'><i class='far fa-edit'></i></a>";
                        }
                        if ($user->can('diagnostic_items_delete')){
                            $btn = $btn."<a type='button' class='btn btn-sm btn-danger delete' data-url='$delete_url' data-title='Are you sure, you want to delete this bill?' class='btn btn-xs btn-primary delete' type='button'><i class='fas fa-trash'></i></a>";
                        }

                        $btn."</div>";

                        return $btn;
                    })
                    ->rawColumns(['action','patient','payment_status'])
                    ->make(true);

        return $tableData;
    }

    public  function generatePathologyBillCode() 
    {
        $setting = Setting::where('key','system_title')->first();
        
        if(!empty($setting)){
            $system_title = $setting->value;
        }

        $prefix = isset($system_title) ? getInitialism($system_title) : getInitialism(env('APP_NAME'));

        $record = PathologyBilling::orderBy('id','desc')->first();
        
        $expNum = explode($prefix.date('y').'PB', $record->code ?? $prefix.date('y').'PB'.'000000');
        
        if(!empty($record)){
            $last_prefix = \Str::before($record->code, date('y'));
        }else{
            $last_prefix = "";
        }
        //check first day in a year
        if ( date('y-m-d') == date('y').'-01-01' || $prefix != $last_prefix){
            $nextPathologyBillingCode = $prefix.date('y').'PB'.'000001';
        } else {
            //increase 1 with last invoice number
            $nextPathologyBillingCode =  $prefix.date('y').'PB'.sprintf("%06d",$expNum[1]+1);
        }

        return $nextPathologyBillingCode;
    }

    public function storeBill($request){
        $bill = PathologyBilling::create([
            'code' => $request->code ?? '',
            'patient_id' => $request->patient_id  ?? '',
            'referrer_id' => $request->referrer_id  ?? '',
            'bill_date' => $request->bill_date  ?  date("Y-m-d", strtotime($request->bill_date)) : '',
            'delivery_date' => $request->delivery_date  ? date("Y-m-d", strtotime($request->delivery_date)) : '', 
            'delivery_time' => $request->delivery_time  ? timeFormat($request->delivery_time) : '', 
            'remarks' => $request->remarks  ?? '',
            'sub_total' => $request->sub_total ?? '',
            'tax' => $request->tax  ?? '', 
            'discount' => $request->discount  ?? '0.00',
            'total' => $request->net_payable  ?? '0.00',
            'created_by' => Auth::user()->id ?? '',
 
        ]);
 
 
        foreach($request->item_id as $key=>$item){
            $item_data = Item::with('category')->where('id', $item)->first();
            PathologyBillingDetails::create([
             'pathology_billing_id' => $bill->id ?? '',
             'item_id' => $item  ?? '', 
             'category_id' => $item_data->category->id ?? '',
             'price' => $request->price[$key] ?? '',
             'quantity' => $request->quantity[$key]  ?? '',
             'discount_percentage' => $request->discount_percentage[$key] ?? '0.00',
             'discount_amount' => $request->discount_amount[$key] ?? '0.00',
             'total' => $request->total[$key] ?? '0.00', 
             'created_by' => Auth::user()->id ?? '', 
            ]);
        }

        if(!empty($bill) && isset($request->received_amount) && isset($request->payment_type)){
            $payment = Payment::create([
                'payment_reference' => class_basename($bill),
                'payment_reference_id' => $bill->id,
                'net_payable' => $request->net_payable  ?? '0.00',
                'received_amount' => $request->net_payable > $request->received_amount  ? $request->received_amount : $request->net_payable,
                'due_amount' => $request->net_payable > $request->received_amount  ? $request->net_payable - $request->received_amount : 0,
                'created_by' => Auth::user()->id ?? '', 
            ]);

            PaymentDetails::create([
                'payment_id' => $payment->id,
                'pay_via' => $request->payment_type ?? '',
                'amount' => $request->net_payable > $request->received_amount  ? $request->received_amount : $request->net_payable,
                'created_by' => Auth::user()->id ?? '', 
            ]);
        }
 
        Session::flash('success', 'Bill has been created successfully!');
        
    }

    public function updateBill($request, $id){


        if($request->has('payment_from') && $request->payment_from == 'invoice'){

            $bill = PathologyBilling::where('id', $request->bill_id)->first();

            if(!empty($bill) && $request->amount > 0 && $request->payment_type != ''){

                $payment = Payment::with('details')->where('payment_reference', 'PathologyBilling')->where('payment_reference_id', $request->bill_id)->first();

                if($payment){

                    // dd($request->all());

                    $total_received = $payment->received_amount + $request->amount;
                    $will_receive   = $payment->due_amount >= $request->amount ? $request->amount : $payment->due_amount;
                    $due_amount     = $payment->due_amount >= $request->amount ? ($payment->due_amount - $request->amount) : 0;

                    // dd($payment,$total_received, $payment->net_payable > $total_received,  $payment->net_payable - $payment->received_amount);
                    $payment->update([
                        'received_amount' => $payment->due_amount >= $request->amount ? $total_received : $payment->received_amount + $payment->due_amount,
                        'updated_by' => Auth::user()->id ?? '',
                        'due_amount' => $due_amount,
                    ]);

                    $payment->details()->create([
                        'payment_id' => $payment->id ?? '',
                        'pay_via' => $request->payment_type ?? '',
                        'amount' => $will_receive,
                        'created_by' => Auth::user()->id ?? '', 
                    ]);
                }else{
                    $payment = Payment::create([
                        'payment_reference' => class_basename($bill),
                        'payment_reference_id' => $bill->id,
                        'net_payable' => $bill->total  ?? '0.00',
                        'received_amount' => $bill->total  > $request->amount  ? $request->amount : $bill->total ,
                        'due_amount' => $bill->total  > $request->amount  ? $bill->total  - $request->amount : 0,
                        'created_by' => Auth::user()->id ?? '', 
                    ]);
        
                    PaymentDetails::create([
                        'payment_id' => $payment->id,
                        'pay_via' => $request->payment_type ?? '',
                        'amount' => $bill->total  > $request->amount  ? $request->amount : $bill->total ,
                        'created_by' => Auth::user()->id ?? '', 
                    ]);

                }
            }
     
            Session::flash('success', 'Bill has been updated successfully!');
        
        


        }else{
            $bill = PathologyBilling::where('id', $id)->first();

            $will_deduce = $request->tax + $request->discount;

            PathologyBilling::where('id', $id)->update([
                'code' => $request->code ?? '',
                'patient_id' => $request->patient_id  ?? '',
                'referrer_id' => $request->referrer_id  ?? '',
                'bill_date' => $request->bill_date  ?  date("Y-m-d", strtotime($request->bill_date)) : '',
                'delivery_date' => $request->delivery_date  ? date("Y-m-d", strtotime($request->delivery_date)) : '', 
                'delivery_time' => $request->delivery_time  ? timeFormat($request->delivery_time) : '', 
                'remarks' => $request->remarks  ?? '',
                'sub_total' => $request->sub_total ?? '',
                'tax' => $request->tax  ?? '', 
                'discount' => $request->discount  ?? '',
                'total' => $request->sub_total - $will_deduce,
                'updated_by' => Auth::user()->id ?? '',
     
            ]);
     
     
            foreach($request->item_id as $key=>$item){
                $item_data = Item::with('category')->where('id', $item)->first();
                $bill->details()->updateOrCreate(
                    [
                        'pathology_billing_id' => $bill->id ?? '',
                        'item_id' => $item  ?? '', 
                    ],
                    [
                    'category_id' => $item_data->category->id ?? '',
                    'price' => $request->price[$key] ?? '',
                    'quantity' => $request->quantity[$key]  ?? '',
                    'discount_percentage' => $request->discount_percentage[$key]  ?? 0,
                    'discount_amount' => $request->discount_amount[$key] ?? 0,
                    'total' => $request->total[$key] ?? 0, 
                    'updated_by' => Auth::user()->id ?? '', 
                    'created_by' => Auth::user()->id ?? '', 
                    ]
                );
            }
    
            if(!empty($bill) && isset($request->received_amount) && isset($request->payment_type)){
                
                $payment = Payment::with('details')->where('payment_reference', 'PathologyBilling')->where('payment_reference_id', $id)->first();
                
                $total_received = $payment->received_amount + $request->received_amount;
                $will_receive   = $payment->due_amount >= $request->received_amount ? $request->received_amount : $payment->due_amount;
                $due_amount     = $payment->due_amount >= $request->received_amount ? ($payment->due_amount - $request->received_amount) : 0;
                $payment->update(
                    [
                        'payment_reference_id' => $bill->id ?? '',
                    ],
                    [
                        'payment_reference' => class_basename($bill),
                        'net_payable' => $request->net_payable  ?? '0.00',
                        'received_amount' => $payment->due_amount >= $request->received_amount ? $total_received : $payment->received_amount + $payment->due_amount,
                        'due_amount' => $due_amount,
                        'updated_by' => Auth::user()->id ?? '', 
                    ]);
    
                $payment->details()->update(
                    ['payment_id' => $payment->id ?? ''],
                    [
                        'pay_via' => $request->payment_type ?? '',
                        'amount' => $will_receive,
                        'updated_by' => Auth::user()->id ?? '',
                        'created_by' => Auth::user()->id ?? '',  
                    ]);
            }
     
            Session::flash('success', 'Bill has been updated successfully!');
        
        
        }


    
    }


    public function searchData($data = null)
    {
        $search_query = [];

        $query = PathologyBilling::with([
            "details",
            "payment",
            "patient",
            "referrer",
        ]);

        if(isset($data["search"])){

            $search_query = [
                "search" => $data["search"]
            ];

            $query->where(function($q) use($data){
                $q->where("code", "LIKE", "%".$data["search"]."%");
            });
        }
        
        $query->orderBy("code","ASC");

        if ($this->paginatedList === true) {

            $item_per_page = 25;

            $bills = $query->paginate($item_per_page)->appends($search_query);
            $bills->pagination_summary = get_pagination_summary($bills);
        } else {
            $bills = $query->get();
        }

        return $bills;
    }

} 