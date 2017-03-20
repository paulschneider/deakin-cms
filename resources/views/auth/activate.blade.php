@extends('auth.layout', ['title' => 'Activate Account'])

@section('content')

<h3>{{ Variable::get('activate.title') }}</h3>
<p>{{ Variable::get('activate.help') }}</p>

<form class="m-t" role="form" method="POST" action="{{ url('activate') }}">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" name="activation_code" value="{{ $token }}">

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
		<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password">
	</div>

	<button type="submit" class="btn btn-primary block full-width m-b">Activate password</button>

</form>
@endsection

