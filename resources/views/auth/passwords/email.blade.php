@extends('auth.layout', ['title' => 'Forgot Password'])

@section('content')

<h3>{{ Variable::get('forgot.title') }}</h3>
<p>{{ Variable::get('forgot.help') }}</p>

<form class="m-t" role="form" method="POST" action="{{ url('password/email') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<div class="form-group">
		<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address">
	</div>


	<button type="submit" class="btn btn-primary block full-width m-b">Send Password Reset Link</button>

	<a href="{{ url('login') }}"><small>Login</small></a>

</form>
@endsection
