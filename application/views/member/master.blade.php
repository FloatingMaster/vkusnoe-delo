<!doctype html>
<html>
<head>
	{{ Seovel::title();  }}
	{{ Seovel::description() }}
	{{ Asset::styles();  }}
	{{ Asset::scripts(); }}
</head>
<body>
	@yield('main')
</body>
</html>
