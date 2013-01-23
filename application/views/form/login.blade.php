<html>
<head>
	<title>Авторизация | Вкусное Дело</title>
</head>
<body>
	<div class="login-form">
		{{ Form::open('member/login') }}
    <!-- check for login errors flash var -->
    @if (Session::has('login_error'))
        <span class="error">Username or password incorrect.</span>
    @endif

    <!-- username field -->
    <p>{{ Form::label('username', 'Username') }}</p>
    <p>{{ Form::text('username', Input::old('username')) }}</p>
    @if ($errors->has('username'))
	<span class="error">Invalid username</span>
	@endif

    <!-- password field -->
    <p>{{ Form::label('password', 'Password') }}</p>
    <p>{{ Form::text('password') }}</p>

    <!-- submit button -->
    <p>{{ Form::submit('Login') }}</p>

{{ Form::close() }}
	</div>
</body>
</html>