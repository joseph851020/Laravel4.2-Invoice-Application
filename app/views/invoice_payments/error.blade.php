@extends('layouts.signup')
 
	@section('content')	 
		<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; An Error Occured.</h1>
  	    @if (Session::get('failed_flash_message'))
		<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
		@endif
	@stop
	

@section('footer')
 
@stop
