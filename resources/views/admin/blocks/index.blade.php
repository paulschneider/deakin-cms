@extends('admin.layouts.master', ['title' => "Blocks"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($blocks->count())
                <table class="table table-bordered table-striped table-hover">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th class="col-xs-1">Edit</th>
                        <th class="col-xs-1">Delete</th>
                    </tr>
                    <tbody>
                        @foreach ($blocks as $block)
                            <tr>
                                <td>{{ $block->id }}</td>
                                <td>{{ $block->name }}</td>
                                <td>{{ $block->type }}</td>
                                <td>{!! link_to_route('admin.blocks.edit', 'Edit', $block->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.blocks.delete', 'Delete', $block->id, ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no blocks</p>
            @endif

            @include('common.pagination', ['paginator' => $blocks])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.blocks.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Create block</a>
@endsection