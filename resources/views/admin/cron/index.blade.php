@extends('admin.layouts.master', ['title' => 'Scheduled Tasks'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($jobs->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Command</th>
                            <th>Schedule</th>
                            <th>State</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>{{ $job->command }}</td>
                                <td>{{ $job->schedule }}</td>
                                <td>
                                    @if ($job->online)
                                        Active
                                    @else
                                        Inactive
                                    @endif
                                </td>
                                <td>{!! link_to_route('admin.cron.edit', 'Edit', $job->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.cron.delete', 'Delete', $job->id, ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no scheduled tasks / jobs</p>
            @endif

            @include('common.pagination', ['paginator' => $jobs])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.cron.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add job</a>
@endsection