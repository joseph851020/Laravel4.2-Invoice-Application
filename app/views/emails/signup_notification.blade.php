@extends('layouts.sendmail')

	@section('email_title')
	 <h3>New Signup! Yipee...</h3>
	@stop

	@section('content')
	  
	{{ $firstname }} has signed up for an account <br /> Follow up at: {{ $email }} <br /><br /> Signup Manager @sighted 
	
	@stop
