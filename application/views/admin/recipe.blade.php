@layout('admin.master')

@section('headparts')
	<script type="text/javascript">
		tinyMCE.init({
			theme: "advanced",
			mode : "textareas",
			skin: "thebigreason"
		});
	</script>
@endsection

@section('header')
	@parent Новый рецепт </h1>
@endsection

@section('main')
	<textarea class='recipe-editor'>Здесь будет немного редактора</textarea>
@endsection