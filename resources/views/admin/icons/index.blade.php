@extends('admin.layouts.master', ['title' => 'Icons'])

@section('content')

    <div class="row">
        <div class="col-sm-12">

            @if ($icons->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>SVG</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($icons as $icon)
                            <tr>
                                <td>{{ $icon->id }}</td>
                                <td>{{ $icon->title }}</td>
                                <td><div style="width: 80px;">{!! $icon->svg !!}</div></td>
                                <td>{!! link_to_route('admin.icons.edit', 'Edit', $icon->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.icons.delete', 'Delete', $icon->id, ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no icons</p>
            @endif

            @include('common.pagination', ['paginator' => $icons])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.icons.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add icon</a>
@endsection