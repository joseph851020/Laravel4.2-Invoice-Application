@extends('layouts.signup')

	@section('content')
 
	<div id="signup-wrap">
		
		<h1>Goodbye!</h1>
		
		@if (Session::get('flash_message'))
			<div class="flash success">{{ Session::get('flash_message') }}</div>
		@endif
		
		@if (Session::get('failed_flash_message'))
			<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
		@endif
 
		<div class="">
			<p><a class="btn" href="http://www.integrityinvoice.com">www.integrityinvoice.com</a></p>
		</div>	 
	
	 </div><!-- END signup-wrap -->
 
   @stop