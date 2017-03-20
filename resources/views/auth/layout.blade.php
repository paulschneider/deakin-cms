<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        @if ( ! empty($title))
            {{ $title }} |
        @endif
        Authentication
    </title>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;lang=en' rel='stylesheet' type='text/css'>
    <link href="{{ elixir_source('assets/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ elixir_source('assets/css/admin/admin.css') }}" rel="stylesheet">
    @yield('css')
</head>
<body class="gray-bg">

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div class="m-b">
                <a href="/"><img src="/assets/images/deakin-digital-logo.png" alt="" width="300" class="img-responsive" border="0"></a>
            </div>
            <br>

            @yield('content')

        </div>
    </div>

<script src="{{ asset('/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('/assets/js/bootstrap.js') }}"></script>
@yield('plugins')
<script src="{{ elixir_source('assets/js/admin/admin.js') }}"></script>
@yield('js')

</body>
</html>
