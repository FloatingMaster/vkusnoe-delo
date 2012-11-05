<html>
<head>
	<title>
		@section('title')
			| Администратор Вкусного Дела
		@yield_section
	</title>
	{{ Asset::styles()  }}
	{{ Asset::scripts() }}
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
					{{ View::make('parts.admin-menu') }}
			</div>
		</div>
	</div>
	<div class="container">
		@section('main')
			Главная
		@yield_section
	</div>
</body>
</html>