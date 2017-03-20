<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@if ( ! empty($title)){{ $title }} {{ Variable::get('title.seperator') }}@endif {{ Variable::get('admin.title') }}</title>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en' rel='stylesheet' type='text/css'>
    <link href="{{ elixir_source('assets/css/font-awesome.css') }}" rel="stylesheet">
    @yield('css_plugin')
    <link href="{{ elixir_source('assets/css/admin/admin.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body class="iframe{{ GlobalClass::output('body', true) }}">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @include('common.alerts')
            </div>
        </div>

        @if ( ! empty($title))
            <h1>{{ $title }}</h1>
        @endif

        @yield('content')

    </div>

    <script type="text/javascript">
    var _token = '{{ csrf_token() }}';
    </script>


    <script src="{{ asset('/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/admin/vendors.js') }}"></script>
    @yield('before-admin')
    <script src="{{ elixir_source('assets/js/admin/admin.js') }}"></script>

    @yield('dropzone_template')
    @yield('js')

</body>
</html>
