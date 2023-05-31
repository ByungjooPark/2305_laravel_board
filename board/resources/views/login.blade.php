@extends('layout.layout')

@section('title', 'Login')

@section('contents')
	<H1>LOGIN</H1>
	@include('layout.errorsvalidate')
	<div>{!!session()->has('success') ? session('success') : ""!!}</div>
	<form action="{{route('users.login.post')}}" method="post">
		@csrf
		<label for="email">Email : </label>
		<input type="text" name="email" id="email">
		<label for="password">password : </label>
		<input type="password" name="password" id="password">
		<br><br>
		<button type="submit">Login</button>
		<button type="button" onclick="location.href = '{{route('users.registration')}}'">Registration</button>
	</form>
@endsection