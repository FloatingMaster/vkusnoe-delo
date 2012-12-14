<html>
<head>
	<title>Вход в панель администратора | Вкусное Дело</title>
</head>
<body>
	<div class="login-form">
		{{ Form::open('login') }}
			<!-- check for login errors flash var -->
			@if (Session::has('login_errors'))
				<span class="error">Неверный пароль или имя пользователя</span>
			@endif
			<!-- username field -->
			<p>{{ Form::label('username', 'Логин') }}</p>
			<p>{{ Form::text('username') }}</p>
			<!-- password field -->
			<p>{{ Form::label('password', 'Пароль') }}</p>
			<p>{{ Form::password('password') }}</p>
			<!-- submit button -->
			<p>{{ Form::submit('Войти') }}</p>
		{{ Form::close() }}
	</div>
</body>
</html>