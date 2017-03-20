@extends('auth.layout', ['title' => 'Register'])

@section('content')

<h3>{{ Variable::get('register.title') }}</h3>
<p>{{ Variable::get('register.help') }}</p>

<form class="m-t" role="form" method="POST" action="{{ url('register') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<div class="form-group">
		<input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name">
	</div>

	<div class="form-group">
		<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address">
	</div>

	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="Password">
		<span class="help-block m-b-none">
			Passwords must be longer than 8 characters, contain numbers, upper and lowercase letters and a symbol.
		</span>
	</div>

	<div class="form-group">
		<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password">
	</div>

	<button type="submit" class="btn btn-primary block full-width m-b">Register</button>

</form>
@endsection
