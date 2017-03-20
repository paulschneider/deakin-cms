<div id="attachment-jstree" data-tree-src="{{ route('admin.attachments.tree') }}" data-result-src="{{ $result }}"></div>


@section('css_plugin')
    <link href="{{ asset('/assets/vendor/iconinc-admin-theme/dist/css/plugins/jsTree/style.min.css') }}" rel="stylesheet">
@endsection

@section('js')

    @parent

    <script src="{{ asset('/assets/vendor/iconinc-admin-theme/dist/js/plugins/jsTree/jstree.min.js') }}"></script>
@endsection