@extends('admin.layouts.master', ['title' => 'Revisions for: '. $entity->revision->title])

@section('content')

    <div class="row">
        <div class="col-sm-12">

            @if ($revisions)
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Contact</th>
                            <th class="col-xs-1">Schedules</th>
                            <th class="col-xs-2">Updated</th>
                            <th class="col-xs-1">Status</th>
                            <th class="col-xs-1">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($revisions as $revision)

                            <tr>
                                <td>{{ $revision->id }}</td>
                                <td>
                                @if ($preview)
                                    <a href="{{ route($show, [$entity->id, 'revision' => $revision->id]) }}">{{ $revision->title or 'No title'}}</a></td>
                                @else
                                    {{ $revision->title or 'No title'}}
                                @endif
                                <td>{{ $revision->user->name or 'Deleted user' }}</td>
                                <td>{{ $revision->user->email or 'Deleted email' }}</td>
                                <td>{!! $revision->entity->relatedSchedules($revision->id)->count() ? '<span class="label label-warning">YES</span>' : 'None' !!}</td>
                                <td>{{ $revision->updated_at->format( config('carbon.format.medium') ) }}</td>
                                <td>{{ config('revision.status.'.$revision->status) }}</td>
                                <td>
                                    {!! link_to_route($revert, 'Edit', [$entity->id, 'revision' => $revision->id], ['class' => 'btn btn-outline btn-success btn-xs']) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no revisions</p>
            @endif

            @include('common.pagination', ['paginator' => $revisions])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route($back) }}" class="btn btn-default">&lsaquo; Back to {{ $entity->getTable() }}</a>
@endsection