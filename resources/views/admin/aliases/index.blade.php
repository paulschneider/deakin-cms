@extends('admin.layouts.master', ['title' => 'Aliases'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <table class="table table-bordered table-striped table-hover aliases-table">
                <thead>
                    <tr>
                        <th class="col-xs-4">Old URL</th>
                        <th>New URL</th>
                        <th class="col-xs-1">Type</th>
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
    <a href="{{ route('admin.aliases.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add alias</a>
@endsection

@section('js')
<script>
$(function() {
    $('.aliases-table').DataTable({
        serverSide: true,
        responsive: true,
        "bAutoWidth":false,
        stateSave: true,
        stateDuration: 60 * 5,
        "dom": 'T<"clear">lfrtip',
        ajax: '{!! route('admin.aliases.index.data') !!}',
        columns: [
            { data: 'alias', name: 'alias' },
            { data: 'redirect', name: 'redirect', orderable: false, searchable: false},
            { data: 'type', name: 'type' },
            { data: 'delete', name: 'delete', orderable: false, searchable: false},
        ]
    });
});
</script>
@endsection