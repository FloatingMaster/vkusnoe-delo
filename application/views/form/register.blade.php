<html>
<head>
	<title>Регистрация нового пользователя | Вкусное Дело</title>
</head>
<body>
	<div class="login-form">
		{{ Form::open('register') }}
			<!-- check for register errors flash var -->
			@if (Session::has('register_errors'))
				<span class="error">Неверные данные! Попробуйте ещё раз</span>
			@endif
            <!-- login field -->
			<p>{{ Form::label('login', 'username') }}</p>
			<p>{{ Form::text('login') }}</p>
			<!-- email field -->
			<p>{{ Form::label('email', 'e-mail') }}</p>
			<p>{{ Form::text('email') }}</p>
			<!-- password field -->
			<p>{{ Form::label('password', 'Пароль') }}</p>
			<p>{{ Form::password('password') }}</p>
            <!-- name field -->
			<p>{{ Form::label('name', 'Имя') }}</p>
			<p>{{ Form::text('name') }}</p>
            <!-- family field -->
			<p>{{ Form::label('family', 'Фамилия') }}</p>
			<p>{{ Form::text('family') }}</p>
			<!-- submit button -->
			<p>{{ Form::submit('Войти') }}</p>
		{{ Form::close() }}
	</div>
</body>
</html>