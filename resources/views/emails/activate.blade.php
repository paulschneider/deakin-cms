@extends('emails.general')

@section('content')
    <p>Click here to activate your account: {!! link_to('activate/'.$token) !!}</p>

    <p>This link is valid for {{ config('auth.passwords.users.expire') }} minutes.</p>
@endsection
