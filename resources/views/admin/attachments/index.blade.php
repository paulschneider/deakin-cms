@extends('admin.layouts.master', ['title' => 'Files'])

@section('content')

    <div class="row">
        <div class="col-sm-3">
            @include('admin.attachments.tree', ['result' => route('admin.attachments.index')])
        </div>

        <div class="col-sm-9 file-listing" id="file-listing">
            @include('admin.attachments.listing', ['actions' => true, 'insert' => false])
        </div>
    </div>


    @include('admin.attachments.dropzone', [
        'id' => uniqid(),
        'fullscreen' => true,
        'multiple' => true,
    ])

@endsection

@section('actions')
    <a href="{{ route('admin.attachments.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Upload file</a>
@endsection

@section('js')
    <script>

    $(function() {

        window.TREE_FILE_DATATABLE = $('.attachment-table').DataTable({
            serverSide: true,
            responsive: true,
            "bAutoWidth":false,
            "dom": 'T<"clear">lfrtip',
            ajax: '{!! route('admin.attachments.listing.data') !!}',
            "fnServerParams": function ( aoData ) {
                aoData.id = window.TREE_FILE_ATTACHMENT;
                aoData.actions = true;
                aoData.insert = false;
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false},
                { data: 'thumb', name: 'thumb', orderable: false, searchable: false},
                { data: 'title', name: 'title' },

                { data: 'created_at', name: 'created_at', orderable: true, searchable: false},
                { data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},

                { data: 'slugtext', name: 'slug' },
                { data: 'edit', name: 'edit', orderable: false, searchable: false},
                { data: 'delete', name: 'delete', orderable: false, searchable: false},
            ]
        });
    });
    </script>
@endsection
