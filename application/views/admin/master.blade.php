<html>
<head>
	{{ Seovel::title()  }}
	{{ Asset::styles()  }}
	{{ Asset::scripts() }}
	@yield('headparts')
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
		@section('header')
			<h1>Панель администратора /
		@yield_section

		@yield('main')
	</div>
</body>
</html>