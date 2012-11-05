<!doctype html>
<html>
<head>
	<title>
		@section('title')
			| Вкусное Дело
		@yield_section
	</title>
	@section('meta')
		<meta name='description'
	{{ Asset::styles();  }}
	{{ Asset::scripts(); }}
</head>
<body>
	<div class="wrapper">
	@yield('main')
	</div>
</body>
</html>