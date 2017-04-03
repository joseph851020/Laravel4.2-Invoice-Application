@extends('layouts.sendmail')
 
	@section('content')
	  
	<p>Hi {{ $firstname }}, <p/>
 
	<p>Your subscription payment was successful. You paid &#36;{{ $amount}} via {{ $payment_system }}. </p>
	
	<p>Date paid: {{ $date_paid }}</p>
 
 
    <p>Kind regards, <br />Sighted Sales Team</p>
 
	@stop