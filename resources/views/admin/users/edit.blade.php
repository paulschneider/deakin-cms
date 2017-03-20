<?php GlobalClass::add('body', 'editor'); ?>
@extends('admin.layouts.master', ['title' => 'Edit a User'])

@section('content')

    @if ($user->id == Auth::user()->id)
        <div class="alert alert-info">
            <i class="fa fa-lg fa-bell-o"></i>
            This is <strong>your</strong> account. Be careful not to deactivate your account or remove your roles!
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">

            {!! Form::model($user, array('method' => 'PATCH', 'route' => array('admin.users.update', $user->id), 'class' => 'form-horizontal')) !!}

                {!! FormField::email(['type' => 'text']) !!}

                {!! FormField::name(['type' => 'text']) !!}

                @include('admin.users.roles', ['user' => $user])

                @if(Entrust::Can('admin.users.deactivate'))
                <div class="form-group">
                    <label class="control-label col-sm-2">Status</label>
                    <div class="col-sm-10">
                        <div class="checkbox">

                            @if ($user->active)
                            <label>
                                {!! Form::checkbox('deactivate', 1, 0) !!}
                                Deactivate Account
                            </label>
                            @else
                                Account Inactive
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Update user', array('class' => 'btn btn-primary cancels-navigation-message')) !!}
                    </div>
                </div>

            {!! Form::close() !!}
        </div>
    </div>
@endsection

