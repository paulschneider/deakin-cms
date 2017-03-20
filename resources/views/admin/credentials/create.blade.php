<?php GlobalClass::add('body', ['editor', 'section-credentials']);?>
@extends('admin.layouts.master', ['title' => 'Create a Credential'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.credentials.store'), 'class' => 'form-horizontal', 'files' => true)) !!}

                {!! FormField::title(['type' => 'text', 'class' => 'input-lg', 'placeholder' => 'Credential Title...', 'label-class' => 'sr-only']) !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Summary <small>This is shorter version of the content.</small></h5>
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
                                    'old' => old('logo_id')
                                ])
                                {!! Form::hidden('logo_id', null, ['id' => 'logo_id']) !!}
                            </div>
                        </div>

                        @include('admin.common.entity-color', ['entity' => null, 'colors' => config('sections.credential_colors')])

                    </div>
                </div>

                @include('admin.common.sections', ['entity' => null, 'options' => []])

                @include('admin.common.banners')

                @include('admin.common.meta')

                @include('admin.common.social')

                @include('admin.common.related_links')

                @include('admin.common.menus', ['collpased' => false])

                @include('admin.common.form_online')

                @include('admin.common.schedule')

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::button('Save and publish', array('class' => 'btn btn-primary cancels-navigation-message', 'name' => 'status', 'value' => 'current', 'type' => 'submit')) !!}
                        {{-- I don't think this is needed --}}
                        {{-- Form::button('Preview', array('class' => 'btn btn-success btn-outline', 'name' => 'status', 'value' => 'preview', 'type' => 'submit')) --}}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
