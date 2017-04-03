@extends('layouts.login')

	
	@section('content')
	<div id="login_form">
	<a href="<?php Config::get('app.app_domain') ?>"><img src="{{ URL::asset('integritylogo.png') }}" width="150" height="100" alt="Sighted Invoice" ></a>
	<h1>Reset Your Password Now</h1>
	
	@if (Session::get('flash_message'))
		<div class="flash success">{{ Session::get('flash_message') }}</div>
	@endif
	
	@if (Session::get('failed_flash_message'))
		<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
	@endif
	 
		{{ Form::open(array('url' => URL::to('passwordresets/reset', array(Request::segment(3), Request::segment(4))))) }} 		
 		{{ Form::hidden('token', Request::segment(4)) }}
		 
		<p>{{ Form::label('email', 'Email Address') }}<br />
		 {{ Form::text('email', null, array('required' => true)) }}</p>
		
		<!-- password field -->
		<p>{{ Form::label('password', 'New Password') }}<br />
		 {{ Form::password('password') }}</p>
		 
		 <!-- password field -->
		<p>{{ Form::label('password_confirmation', 'Password Confirmation') }}<br />
		 {{ Form::password('password_confirmation') }}</p>
		 
		<!-- submit button -->
		<p>{{ Form::submit('Create New Password', array('class' => 'btn')) }}</p>
		
		<!-- check for login errors flash var -->
		 
	 

	 {{ Form::close() }}
 
	</div>
 
	@stop