<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\DiagnosticCentre\Entities\ReportTemplate;
use Modules\DiagnosticCentre\Http\Requests\MassDestroyReportTemplateRequest;
use Modules\DiagnosticCentre\Http\Requests\StoreReportTemplateRequest;
use Modules\DiagnosticCentre\Services\ReporTemplatesService;
use Symfony\Component\HttpFoundation\Response;

class ReportTemplatesController extends Controller
{
    protected $reportTemplateService;

    public function __construct()
    {
        $this->reportTemplateService = new ReporTemplatesService(); 
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('diagnostic_report_templates_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['report_templates'] = ReportTemplate::latest()->get();

        if ($request->ajax()) {
            return $this->reportTemplateService->getTableData();
        }

        return view('diagnosticcentre::report_templates.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        abort_if(Gate::denies('diagnostic_report_templates_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data['report_template_code'] = $this->reportTemplateService->generateReporTemplatesCode();

        return view('diagnosticcentre::report_templates.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreReportTemplateRequest $request)
    {
        ReportTemplate::create( array_merge($request->except('_token'), ['created_by' => Auth::user()->id]));
        
        Session::flash('success', 'Report Template of '.$request->name.' has been created successfully!');

        return redirect()->route('diagnostic-centre.report-templates.index');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(ReportTemplate $reportTemplate, Request $request)
    {
        abort_if(Gate::denies('diagnostic_categories_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request->ajax()){
            
            return response()->json(['template' => $reportTemplate]);
        }else{

            return view('diagnosticcentre::report_templates.show', compact('reportTemplate'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(ReportTemplate $reportTemplate)
    {
        abort_if(Gate::denies('diagnostic_report_templates_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('diagnosticcentre::report_templates.edit', compact('reportTemplate'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        ReportTemplate::where('id', $id)->update( array_merge($request->except(['_token','_method']), ['updated_by' => Auth::user()->id]));
        
        Session::flash('success', 'Report Template of '.$request->name.' has been updated successfully!');

        return redirect()->route('diagnostic-centre.report-templates.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('diagnostic_report_templates_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        ReportTemplate::find($id)->delete();

        Session::flash('success', 'Report Template has been deleted successfully!');

        return redirect()->route('diagnostic-centre.report-templates.index');
    }

    public function massDestroy(MassDestroyReportTemplateRequest $request)
    {
        abort_if(Gate::denies('diagnostic_report_templates_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ReportTemplate::whereIn('id', $request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
