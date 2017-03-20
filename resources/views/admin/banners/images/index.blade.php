@extends('admin.layouts.master', ['title' => "{$banner->name} Images"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($images->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($images as $image)
                            <tr>
                                <td>{{ $image->id }}</td>
                                <td>{{ $image->title }}</td>
                                <td>{!! link_to_route('admin.banners.images.edit', 'Edit', [$banner->id, $image->id], ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.banners.images.delete', 'Delete', [$banner->id, $image->id], ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no images for {{ $banner->name }}</p>
            @endif

            @include('common.pagination', ['paginator' => $images])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.banners.index') }}" class="btn btn-default">&lsaquo; Banner groups</a>
    <a href="{{ route('admin.banners.images.create', $banner->id) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add image</a>
@endsection