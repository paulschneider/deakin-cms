<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Create a Menu'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.menus.store'), 'class' => 'form-horizontal')) !!}

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Menu details</h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::title(['type' => 'text']) !!}
                        {!! FormField::stub(['type' => 'text', 'label' => 'Machine Name:']) !!}
                    </div>
                </div>

                @include('admin.common.form_online')

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save menu', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
