@extends('layouts.login')
 
	@section('content')
	 
	<div id="login_form">
 
	<a href="http://www.sighted.com"><img src="{{ URL::asset('integritylogo.png') }}" alt="Sighted Invoice and Expense" style='width:160px;height:89px;'></a>
	<p>Invoicing and expense tracking application</p>
	
	<h1>Log in</h1>
	 
		{{ Form::open(array('route' => 'login')) }}
		
		 <!-- check for login errors flash var -->
		 
		@if (Session::get('flash_message'))
			<div class="flash success">{{ Session::get('flash_message') }}</div>
		@endif
		
		@if (Session::get('failed_flash_message'))
			<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
		@endif
	
		
		 <!-- username field -->
		<p>{{ Form::label('email', 'Email') }}</p>
		<p>{{ Form::text('email') }}</p>
		<!-- password field -->
		<p>{{ Form::label('password', 'Password') }}</p>
		<p>{{ Form::password('password') }}</p>
		<!-- submit button -->
		<p>{{ Form::submit('Login', array('class' => 'btn')) }}</p>

	 {{ Form::close() }}
	 
	  
	 {{ link_to_route('passwordresets', 'Forgot your password?', array(), array('class' => 'link')) }} &nbsp;&nbsp;&nbsp; 
	 <p>Don't have an account yet? <a class="link" href="{{ URL::to('signup') }}">Signup</a><p/> 
	 <!--
	 <a class="link" href="http://www.integrityinvoice.com">www.integrityinvoice.com</a>
	 -->
	 
	</div>
 
	@stop