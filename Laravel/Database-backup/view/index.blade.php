@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.database_backup') }}
        @can('doctors_create')
            <a class="btn-sm btn-primary" style="float: right; margin-left: 5px" href="{{ route('admin.database-backup.create') }}">
                <i class="fas fa-database"></i> Create a Backup 
            </a>
        @endcan
    </div>

    <div class="card-body">
        @if (count($backups))
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>File Name</th>
                        <th>File Size</th>
                        <th>Date</th>
                        <th>Created At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($backups as $backup)
                        <tr>
                            <td><i class="fas fa-caret-right"></i>  {{ $backup['file_name'] }}</td>
                            <td>{{ $backup['file_size'] }}</td>
                            <td>
                                {{ date('Y-m-d, g:ia', strtotime($backup['last_modified'])) }}
                            </td>
                            <td>
                                {{ diff_date_for_humans($backup['last_modified']) }}
                            </td>
                            <td class="text-right">
                                <a class="btn btn-xs btn-primary" href="{{ url('admin/backup/download/'.$backup['file_name']) }}"><i class="fas fa-download"></i>
                                <a class="btn btn-xs btn-danger" style="margin-left: 5px" data-button-type="delete" href="{{ url('admin/backup/delete/'.$backup['file_name']) }}"> <i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-5">
                <h1 class="text-muted">No Backup Has been Found!</h1>
            </div>
        @endif
    </div>
</div>
@endsection