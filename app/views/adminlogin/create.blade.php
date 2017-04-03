@extends('layouts.login')

	
	@section('content')
	<div id="login_form">
	
	<h1>Admin Login</h1>

		{{ Form::open(array('route' => 'adminsuperlogin')) }}
		
		 <!-- check for login errors flash var -->
		 
		@if (Session::get('flash_message'))
			<div class="flash error">{{ Session::get('flash_message') }}</div>
		@endif
	
		
		 <!-- username field -->
		<p>{{ Form::label('email', 'Email') }}</p>
		<p>{{ Form::text('email') }}</p>		
		<!-- password field -->
		<p>{{ Form::label('password', 'Password') }}</p>
		<p>{{ Form::password('password') }}</p>
		
		<p>{{-- Form::label('authcode', 'Auth Code') --}}</p>
		<p>{{-- Form::password('authcode') --}}</p>
		
		<p>{{-- Form::label('pin', 'PIN') --}}</p>
		<p>{{-- Form::password('pin') --}}</p>
		
		<!-- submit button -->
		<p>{{ Form::submit('Login', array('class' => 'btn')) }}</p>

	 {{ Form::close() }}
	 
	 
	</div>
 
	@stop