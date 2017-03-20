<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Create an Alias'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.aliases.store'), 'class' => 'form-horizontal')) !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Alias details</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::alias(['type' => 'text', 'label' => 'Old URL']) !!}
                        {!! FormField::redirects_to(['type' => 'text', 'label' => 'Redirects To', 'field-description' => 'This link has to currently exist as a page or article.']) !!}
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save alias', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
