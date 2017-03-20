@extends('admin.layouts.master', ['title' => 'Vocabularies'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($vocabularies->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="col-xs-1">View Terms</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="col-xs-1">Sort</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vocabularies as $vocabulary)
                            <tr>
                                <td>{!! link_to_route('admin.vocabularies.terms.index', 'View Terms', $vocabulary->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>
                                    {{ $vocabulary->name }}
                                    <br>
                                    <small>{{ $vocabulary->stub }}</small>
                                </td>
                                <td>{{ $vocabulary->description }}</td>
                                <td>
                                    @if (count($vocabulary->terms))
                                        {!! link_to_route('admin.vocabularies.sort', 'Sort', $vocabulary->id, ['class' => 'btn btn-outline btn-primary btn-xs']) !!}
                                    @else
                                        No terms
                                    @endif
                                </td>
                                <td>{!! link_to_route('admin.vocabularies.edit', 'Edit', $vocabulary->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.vocabularies.delete', 'Delete', $vocabulary->id, ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no vocabularies</p>
            @endif

            @include('common.pagination', ['paginator' => $vocabularies])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.vocabularies.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add vocabulary</a>
@endsection