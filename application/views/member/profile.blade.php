@layout('member.master')

@section('title')
	Профиль @parent
@endsection

@section('main')
	<p>User profile:</p>
<ul>
	<li>email: {{ $member_data['email'] }} </li>
    <li>name: {{ $member_data['name'] }} </li>
    <li>family: {{ $member_data['family'] }} </li>
    <li><a href="/member/subscribe/{{ $member_data['login'] }}">Добавить в друзья</a></li>
    <li><a href="/member/send/{{ $member_data['login'] }}">Написать сообщение</a></li>
</ul>
@endsection