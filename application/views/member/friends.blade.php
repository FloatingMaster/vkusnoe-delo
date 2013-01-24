@layout('member.master')

@section('title')
	Друзья @parent
@endsection

@section('main')
	<p>Friends list</p>
<ul>
	@foreach ($friends as $friend)
    	<li><a href="profile/{{ $friend->login }}">{{$friend->get('name')}} {{$friend->get('family')}}</a></li>
	@endforeach
</ul>
@endsection