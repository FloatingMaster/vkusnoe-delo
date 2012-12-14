<!doctype html>
<html>
<head>
	{{ Seovel::title();  }}
	{{ Seovel::description() }}
	{{ Asset::styles();  }}
	{{ Asset::scripts(); }}
</head>
<body>
	<div class="wrapper">
	@yield('main')
	</div>
</body>
</html>
