<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Create a one column WYSIWYG block'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.blocks.store'), 'class' => 'form-horizontal')) !!}

                {!! Form::hidden('block_type', 'one_column_registered') !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Block details</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::name(['type' => 'text']) !!}

                        {!! FormField::categories(['type' => 'select', 'label' => 'Categories', 'options' => $categories]) !!}

                        {!! FormField::region(['type' => 'select', 'label' => 'Region', 'options' => $regions]) !!}
                    </div>
                </div>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Column One</h5>
                    </div>
                    <div class="ibox-content">

                        {!! FormField::col_one_title(['type' => 'text', 'label' => 'Title']) !!}

                        {!! FormField::col_one_class(['type' => 'text', 'label' => 'Class']) !!}

                        {!! FormField::col_one_method(['type' => 'select', 'label' => 'Col one method', 'options' => $methods]) !!}

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
