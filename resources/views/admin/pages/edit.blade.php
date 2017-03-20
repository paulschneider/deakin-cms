<?php GlobalClass::add('body', 'editor');?>
@extends('admin.layouts.master', ['title' => 'Edit a Page'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($page, array('method' => 'PATCH', 'route' => array('admin.pages.update', $page->id), 'class' => 'form-horizontal', 'files' => true)) !!}

                {!! Form::hidden('revision', $page->revision_id) !!}

                {!! FormField::title(['type' => 'text', 'class' => 'input-lg', 'placeholder' => 'Page Title...', 'label-class' => 'sr-only']) !!}

                 <div class="ibox float-e-margins collapsed">
                    <div class="ibox-title">
                        <h5>Summary <small>This is shorter version of the content.</small></h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content summary">
                        {!! FormField::summary(['type' => 'textarea', 'label-class' => 'sr-only', 'class' => 'wysiwyg micro inline-editor']) !!}
                    </div>
                </div>

                @include('admin.common.sections', ['entity' => $page, 'options' => []])

                @include('admin.common.banners', ['entity' => $page])

                @include('admin.common.meta')

                @include('admin.common.social', ['entity' => $page])

                @include('admin.common.related_links', ['entity' => $page])

                @include('admin.common.menus', ['entity' => $page, 'collapsed' => true])

                @include('admin.common.form_online')

                @include('admin.common.schedule', ['entity' => $page])

                <div class="col-sm-12">
                    <div class="form-group">

                        {!! Form::button('Save and publish', array('class' => 'btn btn-primary cancels-navigation-message', 'name' => 'status', 'value' => 'current', 'type' => 'submit')) !!}

                        @if ($page->revision->status == 'current')
                            {!! Form::button('Save as draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @elseif ($page->revision->status == 'archive')
                            {!! Form::button('Update this revision', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'archive', 'type' => 'submit')) !!}
                            {!! Form::button('Save as draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @elseif ($page->revision->status == 'draft')
                            {!! Form::button('Update this draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @endif

                        {{-- I don't think this is needed --}}
                        {{-- Form::button('Preview', array('class' => 'btn btn-success btn-outline', 'name' => 'status', 'value' => 'preview', 'type' => 'submit')) --}}
                    </div>
                </div>


            {!! Form::close() !!}

        </div>
    </div>
@endsection


@section('actions')
    @if ($page->revision->status != 'current')
        <a href="{{ route('admin.pages.show', [$page->id, 'revision' => $page->revision->id]) }}" class="btn btn-primary btn-small btn-outline" target="_blank"><i class="fa fa-eye"></i> Preview Revision</a>
    @endif
    <a href="{{ route('admin.pages.revisions.index', $page->id) }}" class="btn btn-primary btn-small btn-outline"><i class="fa fa-edit"></i> Revisions</a>
    <a href="{{ route('frontend.dynamic.slug', $page->slug) }}" class="btn btn-success btn-small btn-outline"><i class="fa fa-globe"></i> View Published</a>
@endsection
