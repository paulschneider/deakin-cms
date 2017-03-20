<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Create a User'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.users.store'), 'class' => 'form-horizontal')) !!}

                <div class="alert alert-info">
                        <i class="fa fa-lock"></i> Passwords can not be set by admins as a security precaution. An email will be sent to this user to activate their own account as soon as you save.
                </div>

                {!! FormField::email(['type' => 'text']) !!}

                {!! FormField::name(['type' => 'text']) !!}

                @include('admin.users.roles')

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save user', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
