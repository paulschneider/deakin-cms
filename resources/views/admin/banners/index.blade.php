@extends('admin.layouts.master', ['title' => 'Banners'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($banners->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="col-xs-1">View Images</th>
                            <th>Title</th>
                            <th>Machine Name</th>
                            <th class="col-xs-1">Sort</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $banner)
                            <tr>
                                <td>{!! link_to_route('admin.banners.images.index', 'View Images', $banner->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{{ $banner->title }}</td>
                                <td>{{ $banner->stub }}</td>
                                <td>
                                    @if (count($banner->images))
                                        {!! link_to_route('admin.banners.sort', 'Sort', $banner->id, ['class' => 'btn btn-outline btn-primary btn-xs']) !!}
                                    @else
                                        No images
                                    @endif
                                </td>
                                <td>{!! link_to_route('admin.banners.edit', 'Edit', $banner->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.banners.delete', 'Delete', $banner->id, ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no banners</p>
            @endif

            @include('common.pagination', ['paginator' => $banners])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add banner group</a>
@endsection