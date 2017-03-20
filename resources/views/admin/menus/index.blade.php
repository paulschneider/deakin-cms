@extends('admin.layouts.master', ['title' => 'Menus'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            @if ($menus->count())
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="col-xs-1">Sort</th>
                            <th>Title</th>
                            <th>Machine Name</th>
                            <th class="col-xs-1">View Links</th>
                            <th class="col-xs-1">Edit</th>
                            <th class="col-xs-1">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($menus as $menu)
                            <tr>
                                <td>
                                    @if (count($menu->links))
                                        {!! link_to_route('admin.menus.sort', 'Sort', $menu->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}
                                    @else
                                        No links
                                    @endif
                                </td>
                                <td>{{ $menu->title }}</td>
                                <td>{{ $menu->stub }}</td>
                                <td>
                                    {!! link_to_route('admin.menus.links.index', 'View Links', $menu->id, ['class' => 'btn btn-outline btn-primary btn-xs']) !!}
                                </td>
                                <td>{!! link_to_route('admin.menus.edit', 'Edit', $menu->id, ['class' => 'btn btn-outline btn-success btn-xs']) !!}</td>
                                <td>{!! link_to_route('admin.menus.delete', 'Delete', $menu->id, ['class' => 'btn btn-outline btn-danger btn-xs']) !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>There are currently no menus</p>
            @endif

            @include('common.pagination', ['paginator' => $menus])

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add menu</a>
@endsection