@extends('emails.general')

@section('content')
    <?php $message = Variable::get('site.email.autoresponder', ''); ?>

    {!! str_replace('[[greeting_name]]', $greeting_name, $message) !!}
@endsection