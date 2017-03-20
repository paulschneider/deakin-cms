@extends('emails.general')

@section('content')
    <p>Click here to reset your password: {!! link_to('password/reset/'.$token) !!}</p>

    <p>This link is valid for {{ config('auth.passwords.users.expire') }} minutes.</p>
@endsection
