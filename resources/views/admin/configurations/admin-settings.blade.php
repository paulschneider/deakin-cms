@extends('admin.layouts.master', ['title' => 'Admin settings'])

@section('content')
    <div class="row">
        <div class="col-sm-12">

            {!! Form::open(array('route' => array('admin.configurations.admin.settings.save'), 'class' => 'form-horizontal', 'files' => true)) !!}

                {{-- Admin Title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Admin title <small>Shown as the browers title.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'admin__title'}(['type' => 'text', 'default' => Variable::get('admin.title', ''), 'class' => 'input-lg', 'placeholder' => 'Site Title...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Short title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Short site title <small>Shown in the admin interface.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'admin__title__short'}(['type' => 'text', 'default' => Variable::get('admin.title.short', ''), 'class' => 'input-lg', 'placeholder' => 'Site Title...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Search Title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Search text <small>Shown in the top search bar.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'admin__search__bar'}(['type' => 'text', 'default' => Variable::get('admin.search.bar', ''), 'class' => 'input-lg', 'placeholder' => 'Search the system...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Admin welcome --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Welcome message <small>Shown on the dashboard when users login.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'admin__welcome'}(['type' => 'text', 'default' => Variable::get('admin.welcome', ''), 'class' => 'input-lg', 'placeholder' => 'Welcome to...', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Log in title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Login title <small>The title of the log in screen.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'login__title'}(['type' => 'text', 'default' => Variable::get('login.title', ''), 'class' => 'input-lg', 'placeholder' => 'Authentication required', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Log in help --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Login help <small>Help text for the login screen.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'login__help'}(['type' => 'text', 'default' => Variable::get('login.help', ''), 'class' => 'input-lg', 'placeholder' => 'Please login', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Forgot password title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Forgot password title <small>Title for the forgot your password page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'forgot__title'}(['type' => 'text', 'default' => Variable::get('forgot.title', ''), 'class' => 'input-lg', 'placeholder' => 'Forgot Password', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Forgot password help --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Forgot password help <small>Help text for the forgot your password page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'forgot__help'}(['type' => 'text', 'default' => Variable::get('forgot.help', ''), 'class' => 'input-lg', 'placeholder' => 'Passwords can be forgotten. Fill out the form below and we will send you out a reset request.', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Reset password title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Reset password title <small>Title for the password reset page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'reset__title'}(['type' => 'text', 'default' => Variable::get('reset.title', ''), 'class' => 'input-lg', 'placeholder' => 'Reset Password', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Reset password help --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Reset password help <small>Help text for the password reset page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'reset__help'}(['type' => 'text', 'default' => Variable::get('reset.help', ''), 'class' => 'input-lg', 'placeholder' => 'Passwords can be forgotten. Fill out the form below and we will send you out a reset request.', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Register title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Register title <small>Title for the admin registration page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'register__title'}(['type' => 'text', 'default' => Variable::get('register.title', ''), 'class' => 'input-lg', 'placeholder' => 'Register', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Register help --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Register help <small>Help text for the admin registration page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'register__help'}(['type' => 'text', 'default' => Variable::get('register.help', ''), 'class' => 'input-lg', 'placeholder' => 'Please fill out the following fields to register a new account for system access.', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Activate title --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Activate title <small>Title for the admin activation page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'activate__title'}(['type' => 'text', 'default' => Variable::get('activate.title', ''), 'class' => 'input-lg', 'placeholder' => 'Activate', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>

                {{-- Activate help --}}
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Activate help <small>Help text for the admin activation page.</small></h5>
                    </div>
                    <div class="ibox-content">
                        {!! FormField::{'activate__help'}(['type' => 'text', 'default' => Variable::get('activate.help', ''), 'class' => 'input-lg', 'placeholder' => 'Please fill out the following fields to activate your account.', 'label-class' => 'sr-only']) !!}
                    </div>
                </div>


                <div class="col-sm-12">
                    <div class="form-group">
                        {!! Form::submit('Save admin settings', array('class' => 'btn btn-primary')) !!}
                    </div>
                </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
