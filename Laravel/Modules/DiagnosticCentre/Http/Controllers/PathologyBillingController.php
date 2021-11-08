<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Setting;
use PDF;
use Gate;
use File;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\DiagnosticCentre\Entities\Category;
use Modules\DiagnosticCentre\Entities\Item;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PathologyBillingDetails;
use Modules\DiagnosticCentre\Entities\Payment;
use Modules\DiagnosticCentre\Http\Requests\StoreBillRequest;
use Modules\DiagnosticCentre\Services\PathologyBillsService;
use Symfony\Component\HttpFoundation\Response;

class PathologyBillingController extends Controller
{
    protected $pathologyBillsService;

    public function __construct()
    {
        $this->pathologyBillsService = new PathologyBillsService(); 
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('diagnostic_pathology_bills_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['pathology_bills'] = PathologyBilling::with('payment')->get();

        if ($request->ajax()) {
            return $this->pathologyBillsService->getTableData();
        }
        
        return view('diagnosticcentre::pathology-bills.index', $data);
    }

    public function invoice($bill_id){
        abort_if(Gate::denies('diagnostic_pathology_bills_invoice_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data['settings'] = Setting::all();
        $data['pathology_bill'] = PathologyBilling::with('details','patient','referrer')->where('id', $bill_id)->first();
        $data['payment'] = Payment::with('details','collect_by')->where('payment_reference', 'PathologyBilling')->where('payment_reference_id', $bill_id)->first();
        
        if (File::exists(asset('storage/qr-codes/'.$data['pathology_bill']->code.'.png'))) {
        }else{
            $qr_details = route('my-reports.index');
            $image = \QrCode::format('png')
                 ->size(80)->errorCorrection('H')
                 ->generate( $qr_details);
                $output_file = 'qr-codes/'.$data['pathology_bill']->code.'.png';
                Storage::disk('public')->put($output_file, $image); 

        }
        return view('diagnosticcentre::pathology-bills.invoice', $data);
    }

    public function generatePDF($bill_id)
    {
        $data['settings'] = Setting::all();
        $data['pathology_bills'] = PathologyBilling::with('details','patient','referrer')->where('id', $bill_id)->first();

        $contxt = stream_context_create([
            'ssl' => [
            'verify_peer' => FALSE,
            'verify_peer_name' => FALSE,
            'allow_self_signed'=> TRUE
            ]
            ]);

        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
        ->loadView('diagnosticcentre::pathology-bills.invoicePDF', $data)
        ->setPaper('a4', 'potrait')
        ->setWarnings(false);

        $pdf->getDomPDF()->setHttpContext($contxt);
        
        // $output = $pdf->output();

        // $filename = $data['pathology_bills']->code.'.pdf';
        
        // return new Response($output, 200, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' =>  "inline; filename='$filename'",
        // ]);
    
        return $pdf->download($data['pathology_bills']->code.'.pdf');
    }

    public function print($bill_id)
    {
        $data['settings'] = Setting::all();
        $data['pathology_bills'] = PathologyBilling::with('details','patient','referrer')->where('id', $bill_id)->first();

        return view('diagnosticcentre::pathology-bills.invoicePrint', $data);
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        abort_if(Gate::denies('diagnostic_pathology_bills_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['pathology_bill_code'] = $this->pathologyBillsService->generatePathologyBillCode();
        $data['patients'] = Patient::latest()->get();
        $data['referrers'] = Doctor::latest()->get();
        $data['items'] = Item::with('category')->get();

        return view('diagnosticcentre::pathology-bills.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreBillRequest $request)
    {
       $this->pathologyBillsService->storeBill($request);

       return redirect()->route('diagnostic-centre.pathology-bills.index');

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        abort_if(Gate::denies('diagnostic_pathology_bills_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $data['pathology_bills'] = PathologyBilling::with('details','payment','patient','referrer')->where('id', $id)->first();
        return view('diagnosticcentre::pathology-bills.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        abort_if(Gate::denies('diagnostic_pathology_bills_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['patients'] = Patient::latest()->get();
        $data['referrers'] = Doctor::latest()->get();
        $data['items'] = Item::with('category')->get();
        $data['pathology_bills'] = PathologyBilling::with('details','payment','patient','referrer')->where('id', $id)->first();
        return view('diagnosticcentre::pathology-bills.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StoreBillRequest $request, $id)
    {
        $this->pathologyBillsService->updateBill($request,$id);

        return redirect()->route('diagnostic-centre.pathology-bills.index');
 
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('diagnostic_pathology_bills_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pathologyBills = PathologyBilling::find($id);
        
        if (!$pathologyBills) {
            return "Bill Not Found.";
        }

        $pathologyBills->details()->delete();
        $pathologyBills->payment->details()->delete();
        $pathologyBills->payment()->delete();

        if (File::exists(asset('storage/qr-codes/'.$pathologyBills->code.'.png'))) {
            File::delete(asset('storage/qr-codes/'.$pathologyBills->code.'.png'));
        }

        

        $pathologyBills->delete();

        Session::flash('success', 'Successfully deleted');

        return redirect()->route('diagnostic-centre.pathology-bills.index');
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('diagnostic_pathology_bills_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        PathologyBilling::whereIn('id', $request->ids)->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function search(Request $request)
    {

        $search    = $request->q;

        if(isset($request->q)){

            $data['search'] = $request->q;
            $bills = $this->pathologyBillsService->searchData($data);

        }

        if($request->ajax()){

            return response()->json(['bills' => $bills, 'search' => $search, 'page' => $bills->currentPage ?? 1]);

        }else{
            return view("bills.search", compact(["bills", "search"]));

        }
        

    }

}
