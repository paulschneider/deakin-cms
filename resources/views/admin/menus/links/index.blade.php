@extends('admin.layouts.master', ['title' => "{$menu->name} Links"])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <table class="table table-bordered table-striped table-hover links-table">
                <thead>
                    <tr>
                        <th class="id-row">#</th>
                        <th class="col-xs-4">Title</th>
                        <th>Route</th>
                        <th class="col-xs-1">Edit</th>
                        <th class="col-xs-1">Delete</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.menus.index') }}" class="btn btn-default">&lsaquo; Menus</a>
    <a href="{{ route('admin.menus.sort', $menu->id) }}" class="btn btn-default"><i class="fa fa-sort-amount-desc"></i> Sort</a>
    <a href="{{ route('admin.menus.links.create', $menu->id) }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add link</a>
@endsection

@section('js')
<script>
$(function() {
    $('.links-table').DataTable({
        serverSide: true,
        responsive: true,
        "bAutoWidth":false,
        stateSave: true,
        stateDuration: 60 * 5,
        "dom": 'T<"clear">lfrtip',
        ajax: '{!! route('admin.menus.links.data', $menu->id) !!}',
        columns: [
            { data: 'id', name: 'id', searchable: false},
            { data: 'title', name: 'title' },
            { data: 'route', name: 'route' },
            { data: 'edit', name: 'edit', orderable: false, searchable: false},
            { data: 'delete', name: 'delete', orderable: false, searchable: false},
        ]
    });
});
</script>
@endsection