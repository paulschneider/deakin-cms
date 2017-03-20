<?php GlobalClass::add('body', ['editor', 'section-credentials']);?>
@extends('admin.layouts.master', ['title' => 'Edit a Credential'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($credential, array('method' => 'PATCH', 'route' => array('admin.credentials.update', $credential->id), 'class' => 'form-horizontal', 'files' => true)) !!}

                {!! Form::hidden('revision', $credential->revision_id) !!}

                {!! FormField::title(['type' => 'text', 'class' => 'input-lg', 'placeholder' => 'Credential Title...', 'label-class' => 'sr-only']) !!}

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

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Header Bar</h5>
                    </div>
                    <div class="ibox-content summary">
                        <div class="form-group">
                            {!! Form::label('logo_id', 'Logo', ['class' => 'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                @include('admin.attachments.dropzone', [
                                    'id' => uniqid(),
                                    'into' => '#logo_id',
                                    'files' => '.jpg,.png,.gif',
                                    'path' => 'attachments.path.credentials',
                                    'old' => $credential->revision->logo_id
                                ])
                                {!! Form::hidden('logo_id', null, ['id' => 'logo_id']) !!}
                            </div>
                        </div>

                        @include('admin.common.entity-color', ['entity' => $credential, 'colors' => config('sections.credential_colors')])
                    </div>
                </div>

                @include('admin.common.sections', ['entity' => $credential, 'options' => []])

                @include('admin.common.banners', ['entity' => $credential])

                @include('admin.common.meta')

                @include('admin.common.social', ['entity' => $credential])

                @include('admin.common.related_links', ['entity' => $credential])

                @include('admin.common.menus', ['entity' => $credential, 'collapsed' => true])

                @include('admin.common.form_online')

                @include('admin.common.schedule', ['entity' => $credential])

                <div class="col-sm-12">
                    <div class="form-group">

                        {!! Form::button('Save and publish', array('class' => 'btn btn-primary cancels-navigation-message', 'name' => 'status', 'value' => 'current', 'type' => 'submit')) !!}

                        @if ($credential->revision->status == 'current')
                            {!! Form::button('Save as draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @elseif ($credential->revision->status == 'archive')
                            {!! Form::button('Update this revision', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'archive', 'type' => 'submit')) !!}
                            {!! Form::button('Save as draft', array('class' => 'btn btn-primary btn-outline cancels-navigation-message', 'name' => 'status', 'value' => 'draft', 'type' => 'submit')) !!}
                        @elseif ($credential->revision->status == 'draft')
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
    @if ($credential->revision->status != 'current')
        <a href="{{ route('admin.credentials.show', [$credential->id, 'revision' => $credential->revision->id]) }}" class="btn btn-primary btn-small btn-outline" target="_blank"><i class="fa fa-eye"></i> Preview Revision</a>
    @endif
    <a href="{{ route('admin.credentials.revisions.index', $credential->id) }}" class="btn btn-primary btn-small btn-outline"><i class="fa fa-edit"></i> Revisions</a>
    <a href="{{ route('frontend.dynamic.slug', $credential->slug) }}" class="btn btn-success btn-small btn-outline"><i class="fa fa-globe"></i> View Published</a>
@endsection
