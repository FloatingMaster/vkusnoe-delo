@layout('home.master')

@section('title')
	Главная @parent
@endsection

@section('main')
	{{ View::make('parts.banner') }}
	<div class="advert">Социальная сеть для кулинаров и гурманов</div>
@endsection