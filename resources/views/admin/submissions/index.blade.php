<?php $title = ucfirst($type); ?>
@extends('admin.layouts.master', ['title' => "{$title} form submissions"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($submissions->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="col-xs-1">View</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $submission)
                            <tr>
                                <td>{{ $submission->id }}</td>
                                <td>{{ $submission->email }}</td>
                                <td>{{ $submission->status }}</td>
                                <td>{{ $submission->created_at->format('r') }}</td>
                                <td>{!! link_to_route('admin.submissions.type.edit', 'View', [$type, $submission->id], ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.submissions.type.delete', 'Delete', [$type, $submission->id], ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no submissions</p>
            @endif

            @include('common.pagination', ['paginator' => $submissions])

        </div>
    </div>
@endsection