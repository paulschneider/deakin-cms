@extends('auth.layout', ['title' => 'Reset Password'])

@section('content')

<h3>{{ Variable::get('reset.title') }}</h3>
<p>{{ Variable::get('reset.help') }}</p>

<form class="m-t" role="form" method="POST" action="{{ url('password/reset') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="token" value="{{ $token }}">

	<div class="form-group">
		<input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email Address">
	</div>

	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="Choose a new password">
		<span class="help-block m-b-none">
			Passwords must be longer than 8 characters, contain numbers, upper and lowercase letters and a symbol.
		</span>
	</div>

	<div class="form-group">
		<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password">
	</div>

	<button type="submit" class="btn btn-primary block full-width m-b">Reset password</button>

</form>
@endsection

