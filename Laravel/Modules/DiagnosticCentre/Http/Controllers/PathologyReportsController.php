<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use App\Models\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PathologyReport;
use Modules\DiagnosticCentre\Entities\ReportTemplate;
use Modules\DiagnosticCentre\Http\Requests\StorePathologyReportRequest;
use Modules\DiagnosticCentre\Services\pathologyReportsService;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\File;
use PDF;

class PathologyReportsController extends Controller
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
    public function index(Request $request)
    {
        abort_if(Gate::denies('pathology_reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['pathology_reports'] = PathologyReport::with('bill')->get();

        if ($request->ajax()) {
            return $this->pathologyReportsService->getTableData();
        }
        
        return view('diagnosticcentre::pathology_reports.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('pathology_reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['pathology_reports'] = PathologyBilling::with('details', 'patient', 'referrer')->get();

        if ($request->ajax()) {
            return $this->pathologyReportsService->getCreateTableData();
        }

        return view('diagnosticcentre::pathology_reports.create',$data);
    }


    public function createReport($id)
    {
        abort_if(Gate::denies('pathology_reports_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['pathology_report_code'] = $this->pathologyReportsService->generatePathologyReportsCode();

        $data['report_templates'] = ReportTemplate::all();

        $data['pathology_report'] = PathologyBilling::with('details', 'patient', 'referrer')->where('id', $id)->first();

        return view('diagnosticcentre::pathology_reports.createReport',$data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StorePathologyReportRequest $request)
    {
        if ($request->hasFile('report') && $request->report_type == 'upload') {
            $report      = $request->file('report');
            $fileName   = 'pathology_report/'.time() . '.' . $report->getClientOriginalExtension();

            Storage::disk('public')->put($fileName,File::get($report));

            $report_data = $fileName;

        }else{
            $report_data = $request->report;
        }

        PathologyReport::create([
            'code' => $request->code,
            'pathology_billing_id' => $request->pathology_billing_id,
            'reporting_date'=> $request->reporting_date  ? date("Y-m-d", strtotime($request->reporting_date)) : '',
            'report'	=> $report_data,
            'created_by' => Auth::user()->id ?? 1,

        ]);

        Session::flash('success', 'Report has been stored successfully.');

        return redirect()->route('diagnostic-centre.pathology-reports.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        abort_if(Gate::denies('pathology_reports_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['report_templates'] = ReportTemplate::all();

        $data['pathology_report'] = PathologyReport::with('bill')->where('id', $id)->first();

        return view('diagnosticcentre::pathology_reports.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */

    public function edit($id)
    {
        abort_if(Gate::denies('pathology_reports_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['report_templates'] = ReportTemplate::all();

        $data['pathology_report'] = PathologyReport::with('bill')->where('id', $id)->first();

        return view('diagnosticcentre::pathology_reports.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(StorePathologyReportRequest $request, $id)
    {
        if ($request->hasFile('report') && $request->report_type == 'upload') {
            $report      = $request->file('report');
            $fileName   = 'pathology_report/'.time() . '.' . $report->getClientOriginalExtension();

            Storage::disk('public')->put($fileName,File::get($report));

            $report_data = $fileName;

        }else{
            $report_data = $request->report;
        }

        PathologyReport::where('id', $id)->update([
            'code' => $request->code,
            'reporting_date'=> $request->reporting_date  ? date("Y-m-d", strtotime($request->reporting_date)) : '',
            'report'	=> $report_data,
            'updated_by' => Auth::user()->id ?? 1,

        ]);

        Session::flash('success', 'Report has been updated successfully.');

        return redirect()->route('diagnostic-centre.pathology-reports.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('pathology_reports_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pathologyReport = PathologyReport::find($id);

        if (!$pathologyReport) {
            
            Session::flash('failed', 'Report not found!');

            return redirect()->route('diagnostic-centre.pathology-bills.index');
        }

        $pathologyReport->delete();

        Session::flash('success', 'Successfully deleted');

        return redirect()->route('diagnostic-centre.pathology-bills.index');
    }

    public function generatePDF($id)
    {
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
        ->loadView('diagnosticcentre::pathology_reports.pdf', $data)
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

    public function print($id)
    {
        $data['settings'] = Setting::all();
        $data['pathology_report'] = PathologyReport::with('bill')->where('id', $id)->first();

        return view('diagnosticcentre::pathology_reports.print', $data);
    }
}
