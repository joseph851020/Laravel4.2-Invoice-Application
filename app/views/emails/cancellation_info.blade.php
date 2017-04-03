@extends('layouts.sendmail')

	@section('content')
	 
	<h3>Account cancellation</h3>
	{{ $firstname }} has cancelled an account <br /> Follow up at: {{ $email }} <br />
	
	Sighted ID: {{ $tenantID }}<br /> Sighted
	
	@stop
