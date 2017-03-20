@extends('admin.layouts.master', ['title' => "{$vocabulary->name} Terms"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($terms->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Machine Name</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($terms as $term)
                            <tr>
                                <td>{{ $term->id }}</td>
                                <td>{{ $term->name }}</td>
                                <td>{{ $term->stub }}</td>
                                <td>{!! link_to_route('admin.vocabularies.terms.edit', 'Edit', [$vocabulary->id, $term->id], ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.vocabularies.terms.delete', 'Delete', [$vocabulary->id, $term->id], ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no terms for {{ $vocabulary->name }}</p>
            @endif

            @include('common.pagination', ['paginator' => $terms])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.vocabularies.index') }}" class="btn btn-default">&lsaquo; Vocabulary</a>
    <a href="{{ route('admin.vocabularies.sort', $vocabulary->id) }}" class="btn btn-default"><i class="fa fa-sort-amount-desc"></i> Sort</a>
    <a href="{{ route('admin.vocabularies.terms.create', $vocabulary->id) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add term</a>
@endsection