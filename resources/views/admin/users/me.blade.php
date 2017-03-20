<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Edit Me'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($user, array('method' => 'PATCH', 'route' => array('admin.users.me'), 'class' => 'form-horizontal')) !!}

                {!! FormField::email(['type' => 'text']) !!}

                {!! FormField::name(['type' => 'text']) !!}

                {!! FormField::password(['type' => 'password']) !!}

                {!! FormField::password_confirmation(['type' => 'password', 'label' => 'Confirm Password:']) !!}

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Update me', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

