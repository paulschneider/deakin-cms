<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Create a one column WYSIWYG block'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.blocks.store'), 'class' => 'form-horizontal')) !!}

                {!! Form::hidden('block_type', 'one_column_wysiwyg') !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Block details</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::name(['type' => 'text']) !!}

                        {!! FormField::title(['type' => 'text']) !!}

                        {!! FormField::{'class'}(['type' => 'text']) !!}

                        {!! FormField::categories(['type' => 'select', 'label' => 'Categories', 'options' => $categories]) !!}

                        {!! FormField::region(['type' => 'select', 'label' => 'Region', 'options' => $regions]) !!}

                    </div>
                </div>

                <div class="sections">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Content <small>This is the primary content.</small></h5>
                        </div>
                        <div class="ibox-content">
                            <div class="multiple-section">
                                {!! FormField::body(['type' => 'textarea', 'label-class' => 'sr-only', 'class' => 'wysiwyg inline-editor']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                @include('admin.common.form_online')

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save block', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
