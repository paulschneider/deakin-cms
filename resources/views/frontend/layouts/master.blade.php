<!DOCTYPE html>
<!--[if IE 8 ]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->
<head>
    @include('frontend.common.meta')

    <title>@if ( ! empty($title)){{ $title }} {{ Variable::get('title.seperator') }}@endif {{ Variable::get('site.title') }}</title>

    @include('frontend.common.fonts')

    @include('frontend.common.old-browser')

    <link type="text/css" rel="stylesheet" href="{{ elixir_source('assets/css/frontend/screen.css') }}">

    <link href="{{ asset('assets/images/bookmarks/apple-touch-icon.png') }}" rel="apple-touch-icon" />
    <link href="{{ asset('assets/images/bookmarks/apple-touch-icon-76x76.png') }}" rel="apple-touch-icon" sizes="76x76" />
    <link href="{{ asset('assets/images/bookmarks/apple-touch-icon-120x120.png') }}" rel="apple-touch-icon" sizes="120x120" />
    <link href="{{ asset('assets/images/bookmarks/apple-touch-icon-152x152.png') }}" rel="apple-touch-icon" sizes="152x152" />
    <link href="{{ asset('assets/images/bookmarks/apple-touch-icon-180x180.png') }}" rel="apple-touch-icon" sizes="180x180" />
    <link href="{{ asset('assets/images/bookmarks/icon-hires.png') }}" rel="icon" sizes="192x192" />
    <link href="{{ asset('assets/images/bookmarks/icon-normal.png') }}" rel="icon" sizes="128x128" />
    <link href="{{ asset('assets/images/bookmarks/favicon-32x32.png') }}"  rel="shortcut icon" />

    <script>
        window.CMS = { options: {} };
    </script>

    {!! GlobalJs::output('header') !!}

    @include('frontend.common.analytics')

</head>

<body{!! GlobalClass::outputWithAttribute('body', true) !!}>

    <?php

    $segment = Request::segment(1); // XSS Vulnerability, ensure escaped
    $segment = strip_tags($segment);
    $segment = e($segment);

    $homeable = ["home", "fl"];

    if (empty($segment) || in_array($segment, $homeable)) {
        $logo = "assets/images/deakin-digital-logo-white.svg";
    } else {
        $logo = "assets/images/deakin-digital-logo.svg";
    }

    // CloudFlare is agressive. Need to pass UAT.
    if (env('APP_ENV') != 'live') {
        $logo .= '?'.date('Ymdhi');
    }

    ?>

    <!--[if lte IE 9]>
    <div class="ie-warning warning">
      <p>Your browser is out of date. It has known <strong>security flaws</strong> and may not display all features of this and other websites. Learn how to <a href="http://outdatedbrowser.com/en">update your browser</a>.</p>
    </div>
    <![endif]-->

    @if (empty($modal))
        @include('frontend.common.tag-manager')
    @endif

    @include('frontend.common.nav', ['items'=> $menu_main->roots()])

    <div>

        @if (empty($modal))
            <header class="site-header Fixed">
                <div class="animation-absolute">

                    <a class="brand" href="/"><img src="{{ asset($logo) }}"></a>

                    <button class="hamburger hamburger--elastic" type="button" aria-label="Menu" aria-controls="navigation">
                      <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                      </span>
                    </button>

                    <div class="search nav_search visible-lg">
                        {!! Form::open(['route' => 'search', 'method' => 'GET']) !!}

                                {!! FormField::q([
                                    'default' => '',
                                    'type' => 'text',
                                    'class' => 'input-lg text',
                                    'placeholder' => 'Search site',
                                    'label' => 'Search site',
                                    'input-wrap-class' => 'col-sm-12',
                                    'label-class' => 'sr-only'
                                ]) !!}
                            <button class="btn btn-primary">Search</button>
                        {!! Form::close () !!}
                    </div>

                    {{-- <ul class="quick-links">
                        @foreach($menu_quick_links->roots() as $link)
                            <li><a href="{{ $link->url() }}">{{ $link->title }}</a></li>
                        @endforeach
                        </li>
                    </ul> --}}
                </div>
                <div class="site-header-padding shadow"></div>
            </header>
            <div class="site-header-padding"></div>
        @endif

        {{-- page.blade.php --}}
        @yield('page')

    </div>

    <!--[if (gte IE 9)|!(IE)]><!--> <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script> <!--<![endif]-->

    {!! GlobalJs::output('footer') !!}

    <script src="{{ elixir_source('assets/js/bootstrap.js') }}"></script>
    <script src="{{ elixir_source('assets/js/frontend/vendors.js') }}"></script>
    <script src="{{ elixir_source('assets/js/frontend/main.js') }}"></script>
</body>
</html>
