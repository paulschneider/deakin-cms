<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Create a Vocabulary'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.vocabularies.store'), 'class' => 'form-horizontal')) !!}

                <div class="col-sm-12">
                    {!! FormField::name(['type' => 'text']) !!}
                </div>

                <div class="col-sm-12">
                    {!! FormField::stub(['type' => 'text', 'label' => 'Machine Name:']) !!}
                </div>

                <div class="col-sm-12">
                    {!! FormField::description(['type' => 'textarea', 'class' => 'wysiwyg basic']) !!}
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save vocabulary', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
