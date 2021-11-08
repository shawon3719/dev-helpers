<?php

namespace Modules\DiagnosticCentre\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\DiagnosticCentre\Entities\Payment;

class ReportsController extends Controller
{

    public function pathologyDue()
    {
        $data['dues'] = Payment::with('bill')->where('payment_reference', 'PathologyBilling')->where('due_amount','>',0)->get();

        return view('diagnosticcentre::reports.pathologyDue', $data);
    }

    public function pathologyPaid()
    {
        $data['paids'] = Payment::with('bill')->where('payment_reference', 'PathologyBilling')->where('due_amount',0)->get();
        
        return view('diagnosticcentre::reports.pathologyPaid', $data);
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
