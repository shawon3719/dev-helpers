<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use PDF;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PathologyReport;
use Modules\DiagnosticCentre\Services\pathologyReportsService;

class GuestReportController extends Controller
{
    protected $pathologyReportsService;

    public function __construct()
    {
        $this->pathologyReportsService = new pathologyReportsService(); 
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('diagnosticcentre::guest_reports.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('user_id', 'password');

        $billingInfo = PathologyBilling::with('patient','payment')->where('code','like',$credentials['user_id'])->first();
        

        if(!$billingInfo){

            Session::flash('failed','We can not recognize your user id.');
            return back();
            
        }else
        {
            if($billingInfo->patient && $billingInfo->patient->phone){
                if($credentials['password'] == $billingInfo->patient->phone){

                    if($billingInfo->payment && $billingInfo->payment->due_amount <= 0){

                        Session::put('LoggedUser', $billingInfo);
                        Session::flash('success', 'You have successfully Logged-In!');
                        return redirect()->route('my-reports.show');

                    }else{

                        Session::flash('failed','Please clear the due amount in-order to access.');
                        return back();

                    }

                }else{

                    Session::flash('failed','Incorrect password');
                    return back();

                }
            }else{
                Session::flash('failed','Your password does not match any record.');
            }
        }

        Session::flash('failed','Opps! You have entered invalid credentials');
        return back();
    }

    public function show()
    {

        if(Session::has('LoggedUser')){
            $data['billingInfo'] = $billingInfo = Session::get('LoggedUser');
            
            $data['reports'] = PathologyReport::where('pathology_billing_id', $billingInfo->id)->get();
           
            return view('diagnosticcentre::guest_reports.list', $data);
        }
        Session::flash('failed','No user found! Please Login first.');
        return redirect()->route('my-reports.index');
    }

    public function view($id)
    {
        if(Session::has('LoggedUser')){
            $data['pathology_report'] = PathologyReport::with('bill')->where('id', $id)->first();

            return view('diagnosticcentre::guest_reports.show',$data);
        }
        Session::flash('failed','No user found! Please Login first.');
        return redirect()->route('my-reports.index');
    }


    public function generatePDF($id)
    {
        if(Session::has('LoggedUser')){
            $data['settings'] = Setting::all();
            $data['pathology_report'] = PathologyReport::with('bill')->where('id', $id)->first();

            $contxt = stream_context_create([
                'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
                ]
                ]);

            $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
            ->loadView('diagnosticcentre::guest_reports.pdf', $data)
            ->setPaper('a4', 'potrait')
            ->setWarnings(false);

            $pdf->getDomPDF()->setHttpContext($contxt);
            
            // $output = $pdf->output();

            // $filename = $data['pathology_bills']->code.'.pdf';
            
            // return new Response($output, 200, [
            //     'Content-Type' => 'application/pdf',
            //     'Content-Disposition' =>  "inline; filename='$filename'",
            // ]);
        
            return $pdf->download($data['pathology_report']->code.'.pdf');
        }
        Session::flash('failed','No user found! Please Login first.');
        return redirect()->route('my-reports.index');
            
    }

    public function print($id)
    {
        if(Session::has('LoggedUser')){
            $data['settings'] = Setting::all();
            $data['pathology_report'] = PathologyReport::with('bill')->where('id', $id)->first();

            return view('diagnosticcentre::guest_reports.print', $data);
        }
        Session::flash('failed','No user found! Please Login first.');
        return redirect()->route('my-reports.index');
    }

    public function logout()
    {
        if(Session::has('LoggedUser'))
        {
            Session::forget('LoggedUser');
        };

        return redirect()->route('my-reports.index');
    }


}
