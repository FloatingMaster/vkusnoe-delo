<html>
<head>
	<title>Личное сообщение | Вкусное Дело</title>
</head>
<body>
	<div class="login-form">
		{{ Form::open('member/send') }}
    <!-- check for login errors flash var -->
    @if (Session::has('send_error'))
        <span class="error">Произошла ошибка. Попробуйте ещё раз.</span>
    @endif

    <!-- to field -->
    <p>{{ Form::label('to', 'Кому') }}</p>
    <p>{{ Form::text('to', (is_null($to))?Input::old('to'):$to) }}</p>
    @if ($errors->has('to'))
	<span class="error">Получатель не найден</span>
	@endif

    <!-- text field -->
    <p>{{ Form::label('text', 'Сообщение') }}</p>
    <p>{{ Form::text('text') }}</p>

    <!-- submit button -->
    <p>{{ Form::submit('Send') }}</p>

{{ Form::close() }}
	</div>
</body>
</html>