@layout('admin.master')

@section('headparts')
	<script type="text/javascript">
		tinyMCE.init({
			theme: "advanced",
			mode : "textareas",
			skin: "thebigreason",
            plugins: "images"
		});
	</script>
@endsection

@section('header')
	@parent Новый рецепт </h1>
@endsection

@section('main')
    <form action="/admin/recipe/new" class="recipe">
        <input type="text" class="recipe-title" placeholder="Название рецепта">
    	<textarea class='recipe-content'>Здесь будет немного редактора</textarea>
        <button class="recipe-submit">Опубликовать</button>
    </form>
@endsection