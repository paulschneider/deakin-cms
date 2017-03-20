@extends('auth.layout', ['title' => 'Authentication Required'])

@section('content')

<h3>{{ Variable::get('login.title') }}</h3>
<p>{{ Variable::get('login.help') }}</p>

<form class="m-t" role="form" method="POST" action="{{ url('login') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}" autocomplete="off">

	<div class="form-group">
		<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address" autocomplete="on">
	</div>

	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="Password">
	</div>

	<div class="form-group">
		<div class="checkbox">
			<label>
				<input type="checkbox" name="remember"> Remember Me
			</label>
		</div>
	</div>

	<button type="submit" class="btn btn-primary block full-width m-b">Login</button>
	<a href="{{ url('password/reset') }}"><small>Forgot password?</small></a>
</form>
@endsection
