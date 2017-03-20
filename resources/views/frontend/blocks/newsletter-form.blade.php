<?php
    GlobalClass::add('body', ['newsletter-form', 'use-abide']);
?>

<div class="row">
    <div class="form-wrapper">
        <div class="form-header">
            <div class="row">
                <h3>Sign-Up to DeakinDigital</h3>
            </div>
        </div>
        <div class="form-content">
            <div class="row form-content-row">
                <div class="col-sm-7 col-left">
                    {!! Form::open(array('route' => array($route), 'id' => 'newsletter-form', 'data-abide' => '')) !!}
                    {!! Form::hidden('redirect', $redirect) !!}
                    {!! Form::hidden('type', 'contact') !!}
                    {!! Form::hidden('modal', (Request::has('modal') ? 'true' : null)) !!}

                    <div class="form-group">
                        <h3>Sign up to newsletter</h3>
                        <p>
                            Discover the latest news, research, events and special offers from DeakinDigital.
                        </p>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                {!! Form::label('name', 'First name', array('class' => 'required')) !!}
                                {!! Form::text('name', null, array('class' => 'form-control', 'required' => '', 'placeholder' => 'Jane')) !!}
                            </div>
                            <div class="col-sm-6">
                                {!! Form::label('surname', 'Last name', array('class' => 'required')) !!}
                                {!! Form::text('surname', null, array('class' => 'form-control', 'required' => '', 'placeholder' => 'Smith')) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('email', 'Your email', array('class' => 'required')) !!}
                        {!! Form::email('email', null, array('class' => 'form-control', 'required' => '', 'placeholder' => 'jane.smith@email.com.au')) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::checkbox('agree', 1, null, array('id' => 'agree', 'class' => 'required', 'required' => '')) !!}
                        <label for="agree" class="agree required">I agree to the <a href="{{ url('terms-and-conditions') }}" target="_blank">Terms &amp; Conditions</a> and the <a href="{{ url('data-collection-notice') }}" target="_blank">Data Collection Notice</a></label>
                    </div>

                    <div class="form-group">
                        <div class="submit-btn-container">
                           {!! Form::submit('Sign up', ['class' => 'btn btn-primary btn-block']) !!}
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

                <div class="col-sm-4 col-sm-offset-1 col-right">

                    <div class="form-group">
                        <h3>Register</h3>
                        <p>
                            Register with us to get exclusive access to our members area and receive the latest news from DeakinDigital.
                        </p>
                    </div>

                    <div class="form-group">
                        <div class="submit-btn-container">
                            <a href="https://my.deakindigital.com/register" class="btn btn-primary btn-block" target="_top">Register</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
