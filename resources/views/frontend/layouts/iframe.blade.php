<!DOCTYPE html>
<!--[if IE 8 ]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
    @include('frontend.common.meta')

    <title>@if ( ! empty($title)){{ $title }} {{ Variable::get('title.seperator') }}@endif {{ Variable::get('site.title') }}</title>

    @include('frontend.common.fonts')

    <link type="text/css" rel="stylesheet" href="{{ elixir_source('assets/css/frontend/screen.css') }}">

    <script>
        window.CMS = { options: {} };
    </script>

    {!! GlobalJs::output('header') !!}

</head>

<body class="iframe {{ GlobalClass::output('body', true) }}">

    <main class="container">
        @yield('page')
    </main>

    <!--[if (gte IE 9)|!(IE)]><!--> <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script> <!--<![endif]-->

    @include('frontend.common.analytics')

    {!! GlobalJs::output('footer') !!}

    <script src="{{ elixir_source('assets/js/bootstrap.js') }}"></script>
    <script src="{{ elixir_source('assets/js/frontend/main.js') }}"></script>

</body>
</html>
