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
<body{!! GlobalClass::outputWithAttribute('body', true) !!}>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs">
                                        <strong class="font-bold">{{ Auth::user()->name }}</strong>
                                    </span>

                                    <span class="text-muted text-xs block">{{ Auth::user()->role_names }} <b class="caret"></b></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="{{ route('admin.users.me') }}">Profile</a></li>
                                <li class="divider"></li>
                                <li><a href="{{ url('logout') }}">Logout</a></li>
                            </ul>
                    </div>
                    <div class="logo-element">
                        {{ Variable::get('admin.title.short') }}
                    </div>
                </li>
                @if (isset($menu_admin))
                    {{-- To add links during dev, edit the seed. --}}
                    @include('admin.layouts.nav', ['items'=> $menu_admin->roots(), 'default_icon' => 'fa-th-large'])
                @endif
            </ul>
        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i></a>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li><span class="m-r-sm text-muted welcome-message">{{ Variable::get('admin.welcome') }}</span></li>
                    <li class="divider"></li>
                    <li><a href="/"><i class="fa fa-globe"></i> <strong>Visit Website</strong></a></li>
                </ul>
            </nav>
        </div>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-12">

                <div class="title-action pull-right">
                    @yield('actions')
                </div>

                <h2>{{ $title or 'Administration System' }}</h2>

                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.index') }}">Dashboard</a></li>

                    @foreach (Menus::getBreadcrumbs('admin') as $trail)
                    <li class="{{ $trail->class }}">
                        @if ($trail->active)
                            <strong>{{ $trail->title }}</strong>
                        @else
                            <a href="{{ $trail->url }}">{{ $trail->title }}</a>
                        @endif
                    </li>
                    @endforeach

                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content{{ GlobalClass::output('wrapper-content', true) }}">
                    <div class="row">
                        <div class="col-sm-12 toastr-alerts">
                            @include('common.alerts')
                        </div>
                    </div>

                    @yield('content')

                </div>
            </div>
        </div>
    </div>
</div>


    <!-- Scripts -->
<script type="text/javascript">
var _token = '{{ csrf_token() }}';
var _ckImageSizes = {!! json_encode(array_keys(config('attachments.styles.sizes'))) !!};
</script>

<script src="{{ asset('/assets/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
@yield('before-admin')
<script src="{{ elixir_source('assets/js/admin/vendors.js') }}"></script>
<script src="{{ elixir_source('assets/js/admin/admin.js') }}"></script>
@yield('dropzone_template')
@yield('js')
</body>
</html>
