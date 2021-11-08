<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DiagnosticCentre\Entities\PathologyBilling;
use Modules\DiagnosticCentre\Entities\PaymentDetails;
use Modules\DiagnosticCentre\Services\DiagnosticService;

class DiagnosticCentreController extends Controller
{
    protected $diagnosticService;

    public function __construct()
    {
        $this->diagnosticService = new DiagnosticService(); 
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data = $this->diagnosticService->getIndexItem();

        return view('diagnosticcentre::index', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('diagnosticcentre::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('diagnosticcentre::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('diagnosticcentre::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
