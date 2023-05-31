<h2>Header</h2>

{{-- 로그인 상태 --}}
@auth
	<div><a href="{{route('users.logout')}}">로그아웃</a></div>
	<div><a href="{{route('users.edit')}}">회원정보 수정</a></div>
@endauth

{{-- 비로그인 상태 --}}
@guest
	<div><a href="{{route('users.login')}}">로그인</a></div>
@endguest
<hr>