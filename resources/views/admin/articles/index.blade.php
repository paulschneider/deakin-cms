@extends('admin.layouts.master', ['title' => 'Articles'])

@section('content')

    <div class="row">
        <div class="col-sm-12">

            <table class="table table-bordered table-striped table-hover articles-table">
                <thead>
                    <tr>
                        <th class="id-row">#</th>
                        <th class="col-xs-4">Title</th>
                        <th>Slug</th>
                        <th class="col-xs-1">Online</th>
                        <th class="col-xs-1">Revisions</th>
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
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary"><i class="fa fa-plus-circle"></i>  Add article</a>
@endsection

@section('js')
<script>
$(function() {
    $('.articles-table').DataTable({
        serverSide: true,
        responsive: true,
        "bAutoWidth":false,
        stateSave: true,
        stateDuration: 60 * 5,
        "dom": 'T<"clear">lfrtip',
        ajax: '{!! route('admin.articles.index.data') !!}',
        columns: [
            { data: 'id', name: 'id', searchable: false},
            { data: 'title', name: 'article_revisions.title' },
            { data: 'slug', name: 'slug' },
            { data: 'published', name: 'published', orderable: true, searchable: false},
            { data: 'revisions', name: 'revisions', orderable: false, searchable: false},
            { data: 'edit', name: 'edit', orderable: false, searchable: false},
            { data: 'delete', name: 'delete', orderable: false, searchable: false},
        ]
    });
});
</script>
@endsection
