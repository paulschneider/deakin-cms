<?php GlobalClass::add('body', ['has-sections', 'collapsibles', 'section-page']);?>

@extends('frontend.layouts.page', ['title' => $page->revision->title])

@section('admin-links')
    @if (Entrust::can('admin.pages.post'))
        <a href="{{ route('admin.pages.edit', $page_id) }}" class="edit-link btn btn-xs btn-primary">
            <i class="fa fa-cog"></i> Edit
        </a>
    @endif
@endsection


@section('content')

    @if (count($sections))

        <article class="sections">
            <div class="section-multiple-fields">
                @foreach ($sections as $section)
                    {!! $section !!}
                @endforeach
            </div>
        </article>

    @endif

    @include('frontend.common.related-links')

@endsection
