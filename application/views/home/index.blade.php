@layout('home.master')

@section('title')
	Главная @parent
@endsection

@section('main')
	{{ View::make('parts.banner') }}
	<p class="cursive">Скоро открытие!</p>
@endsection