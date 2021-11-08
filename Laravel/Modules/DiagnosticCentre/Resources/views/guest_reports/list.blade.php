@extends('diagnosticcentre::layouts.auth')

@section('content')
@include('diagnosticcentre::guest_reports.nav')
<div class="card" style="padding: 10px!important">
    <div class="card-header">
        {{ trans('diagnosticcentre::cruds.pathology-report.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th>Report No.</th>
                        <th>Reporting Date</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{$report->code}}</td>
                            <td>{{$report->reporting_date}}</td>
                            <td>
                                <div class='btn-group' role='group' aria-label='Basic example'>
                                    <a type='button' class='btn btn-sm btn-success' href='{{route('my-reports.view', $report->id)}}'><i class='far fa-eye'></i></a>
                                    @if(Storage::disk('public')->exists($report->report))
                                        <a target="_blank" type='button' class='btn btn-sm btn-primary' href='{{Storage::url($report->report)}}'><i class="fas fa-download"></i></a>
                                    @else
                                        <a target="_blank" type='button' class='btn btn-sm btn-primary' href='{{route('my-reports.pdf', $report->id)}}'><i class="fas fa-download"></i></a>
                                    @endif
                                    {{-- <a type='button'  class="btn btn-sm btn-danger" href="{{ route('my-reports.pdf', $report->id) }}"><i class="far fa-file-pdf"></i></a>
                                    <a type='button'  class="btn btn-sm btn-secondary" href="{{  route('my-reports.print', $report->id) }}"><i class="fas fa-print"></i></a>  --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
<script>
    let menuIcon = document.querySelector('.menuIcon');
        let nav = document.querySelector('.overlay-menu');

        menuIcon.addEventListener('click', () => {
            if (nav.style.transform != 'translateX(0%)') {
                nav.style.transform = 'translateX(0%)';
                nav.style.transition = 'transform 0.2s ease-out';
            } else { 
                nav.style.transform = 'translateX(-100%)';
                nav.style.transition = 'transform 0.2s ease-out';
            }
        });


        // Toggle Menu Icon ========================================
        let toggleIcon = document.querySelector('.menuIcon');

        toggleIcon.addEventListener('click', () => {
            if (toggleIcon.className != 'menuIcon toggle') {
                toggleIcon.className += ' toggle';
            } else {
                toggleIcon.className = 'menuIcon';
            }
        });
</script>
<script>

    let delete_data = {
        permission: "no_need",
        url: ""
    };

    let coloumn_data = [
        {data: 'code'},
        {data: 'bill'},
        {data: 'patient'},
        {data: 'delivery_date'},
        {data: 'action', orderable: false, searchable: false},
    ];

</script>
@endsection