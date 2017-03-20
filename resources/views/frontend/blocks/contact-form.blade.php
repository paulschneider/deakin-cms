<?php
    $reason = Request::get('reason', null);
    if ($reason && array_key_exists($reason, $options['reason'])) {
        GlobalClass::add('body', ['has-reason']);
        $reasonTitle = $options['reason'][$reason];
    } else {
        $reasonTitle = current($options['reason']);
    }

    GlobalClass::add('body', ['contact-form', 'use-abide']);
?>

<div class="row">
    <div class="form-wrapper">
        <div class="form-header">
            <div class="row">
                <h3>{{ $reasonTitle }}</h3>
            </div>
        </div>

        <div class="form-content">

            {!! Form::open(array('route' => array($route), 'id' => 'contact-form', 'data-abide' => '')) !!}
            {!! Form::hidden('redirect', $redirect) !!}
            {!! Form::hidden('type', 'contact') !!}
            {!! Form::hidden('modal', (Request::has('modal') ? 'true' : null)) !!}

            <div class="form-group sr-only">
                {!! Form::select('reason', $options['reason'], $reason, array('class' => 'form-control', 'required' => '')) !!}
            </div>

            <div class="form-group">
            	{!! Form::label('name', 'Your name', array('class' => 'required')) !!}
            	{!! Form::text('name', null, array('class' => 'form-control', 'required' => '', 'placeholder' => 'John')) !!}
            </div>

            <div class="form-group">
            	{!! Form::label('email', 'Your email', array('class' => 'required')) !!}
            	{!! Form::email('email', null, array('class' => 'form-control', 'required' => '', 'placeholder' => 'john.smith@email.com.au')) !!}
            </div>

            <div class="form-group">
            	{!! Form::label('message', 'Your message', array('class' => 'required')) !!}
            	{!! Form::textarea('message', null, ['class' => 'form-control', 'placeholder' => 'Your message...', 'required' => '']) !!}
            </div>

            @include('common.recaptcha')

            <div class="form-group">
                <div class="submit-btn-container">
            	   {!! Form::submit('Send enquiry', ['class' => 'btn btn-primary btn-block']) !!}
                </div>
            </div>

            {!! Form::close() !!}
        </div>

    </div>
</div>
