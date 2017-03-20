{{-- There is a reason why this template exists, so that global class can be passed up --}}
<?php

    $segment = Request::segment(1); // XSS Vulnerability, ensure escaped
    $segment = strip_tags($segment);
    $segment = e($segment);

    $classes = ['section-' . $segment];
    if (!empty($banner)) {
        $classes[] = 'has-banner';
    }
   
    if (empty($segment) || $segment == 'home') {
        $classes[] = 'home';
    } else {
        $classes[] = 'section-default';
    }

    $modal = Request::has('modal');

    if ($modal) {
        $classes[] = 'iframe';
    }

    GlobalClass::add('body', $classes);
    GlobalClass::add('body', 'has-video');
?>

@extends('frontend.layouts.master', ['modal' => $modal])

@section('page')

    @if (!$modal)
        {!! $banner or null !!}
    @endif

    <main class="main-wrapper">
        <header class="container main-header">

            @yield('admin-links')

            @if ( ! empty($title) && empty($banner))
                <h1 class="{{ heading_class($title) }}">{{ $title }}</h1>

                @include('common.breadcrumbs')

            @endif
        </header>

        {{-- (pages,articles)/show.blade.php --}}
        @yield('content')
    </main>

    @if (!$modal)
        @include('frontend.common.footer')
    @endif
@endsection
