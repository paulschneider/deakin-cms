@extends('admin.layouts.iframe', ['title' => 'Files'])

@section('content')

    <div class="row">
        <div class="col-sm-3">
            @include('admin.attachments.tree', ['result' => route('admin.attachments.iframe')])
        </div>

        <div class="col-sm-9 file-listing" id="file-listing">
            @include('admin.attachments.listing', ['actions' => false, 'insert' => true])
        </div>
    </div>

    @include('admin.attachments.dropzone', [
        'id' => uniqid(),
        'fullscreen' => true,
        'multiple' => true,
    ])

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
                aoData.actions = false;
                aoData.insert = true;
            },
            columns: [
                { data: 'action', name: 'action', orderable: false, searchable: false},
                { data: 'thumb', name: 'thumb', orderable: false, searchable: false},
                { data: 'title', name: 'title' },
                { data: 'updated_at', name: 'updated_at', orderable: true, searchable: false},
            ]
        });
    });
    </script>
@endsection
