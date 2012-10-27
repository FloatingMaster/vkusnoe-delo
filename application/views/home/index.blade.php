<!doctype html>
<html>
<head>
	<title>
		@section('title')
			Вкусное Дело | Социальная сеть для кулинаров
		@yield_section
	</title>
	{{ Asset::styles();  }}
	{{ Asset::scripts(); }}
</head>
<body>
	@section('main')
	<div class='container'>
		Test
	</div>
	@yield_section
</body>
</html>