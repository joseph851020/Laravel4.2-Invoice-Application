@extends('layouts.login')

	
	@section('content')
	<div id="login_form">
	<a href="<?php echo Config::get('app.app_domain') ?>"><img src="{{ URL::asset('integritylogo.png') }}" width="150" height="100" alt="Integrity Invoice" ></a>
	<h1>Reset your password</h1>

		{{ Form::open(array('url' => 'passwordresets', 'method' => 'POST')) }}
 
		 <!-- username field -->
		<p>{{ Form::label('email', 'Email Address') }}</p>
		<p>{{ Form::text('email', null, array('required' => true)) }}</p>
		<!-- password field -->
		 
		<!-- submit button -->
		<p>{{ Form::submit('Reset', array('class' => 'btn')) }}</p>
		
		<!-- check for login errors flash var -->
		
		@if (Session::get('flash_message'))
		<div class="flash success">{{ Session::get('flash_message') }}</div>
		@endif
		
		@if (Session::get('failed_flash_message'))
			<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
		@endif
	 
	 {{ Form::close() }}
	 
	  <br />
	 	<a class="link" href="{{ URL::to('login') }}">Login</a> &nbsp;&nbsp;&nbsp;
	    <a class="link" href="<?php echo Config::get('app.app_domain') ?>"><?php echo Config::get('app.app_domain') ?></a>
 
	</div>
 
	@stop