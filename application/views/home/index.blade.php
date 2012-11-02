@layout('home.master')

@section('title')
	Главная @parent
@endsection

@section('main')
	<div class="ribbon">
		<div class="stripes"></div>
		<span class="corner right"></span>
		<span class="corner left"></span>
		<div class="banner">Вкусное Дело</div>
	</div>
	<!-- <div class="notice">Все права защищены</div> -->
@endsection