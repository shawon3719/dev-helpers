@extends('diagnosticcentre::layouts.master')
@section('content')
<div class="card">
    <div class="card-header">
        Due Bill List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.bill_code') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.total_bill') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.discount') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.tax') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.paid') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.due_amount') }}</th>
                        <th>{{ trans('diagnosticcentre::cruds.pathology-report.fields.last_payment') }}</th>
                       
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_net_payable = 0;
                        $total_discount = 0;
                        $total_tax = 0;
                        $total_received_amount = 0;
                        $total_due_amount = 0;
                    @endphp
                    @foreach($dues as $key => $due)
                        <tr data-entry-id="{{ $due->id }}">
                            <td>{{ $due->bill->code ?? '' }}</td>
                            <td>{{ $due->net_payable ?? 0 }}</td>
                            <td>{{ $due->bill->discount ?? 0 }}</td>
                            <td>{{ $due->bill->tax ?? 0 }}</td>
                            <td>{{ $due->received_amount ?? 0 }}</td>
                            <td>{{ $due->due_amount ?? 0 }}</td>
                            <td>{{ Carbon\Carbon::parse($due->created_at)->format($settings['date_format'] ?? 'j F, Y') }}</td>
                            <td>
                                @can('diagnostic_report_templates_show')
                                    <a type='button' class='btn btn-sm btn-primary' href="{{ route('diagnostic-centre.pathology-bills.invoice', $due->bill->id) }}"><i class='far fa-eye'></i></a>
                                @endcan
                            </td>
                        </tr>
                        @php
                        $total_net_payable += $due->net_payable;
                        $total_discount += $due->bill->discount;
                        $total_tax += $due->bill->tax;
                        $total_received_amount += $due->received_amount;
                        $total_due_amount += $due->due_amount;
                    @endphp
                    @endforeach
                    @if (count($dues) > 0)
                        <tr>
                            <td></td>
                            <td style="font-style: bold!important">{{ $total_net_payable ?? 0 }}</td>
                            <td style="font-style: bold!important">{{ $total_discount ?? 0 }}</td>
                            <td style="font-style: bold!important">{{ $total_tax ?? 0 }}</td>
                            <td style="font-style: bold!important">{{ $total_received_amount ?? 0 }}</td>
                            <td style="font-style: bold!important">{{ $total_due_amount ?? 0 }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')

<script>
      //CK Editor 
  DecoupledEditor
    .create( document.querySelector( '.document-editor__editable' ), {} )
    .then( editor => {
        const toolbarContainer = document.querySelector( '.document-editor__toolbar' );

        toolbarContainer.appendChild( editor.ui.view.toolbar.element );

        window.editor = editor;
        
        editor.model.document.on( 'change', () => {
          $('#template').val(editor.getData());
        });
    } )
    .catch( err => {
        console.log( err );
    } );
</script>

@endsection