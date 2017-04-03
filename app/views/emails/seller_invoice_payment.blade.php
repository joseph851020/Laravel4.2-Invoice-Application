@extends('layouts.payment_notification')
 
	@section('content')
	  
	<p>Hi {{ $firstname }}, <p/>
 
	<p>Your client: {{ $client_company }} has made a payment for Invoice {{ $tenant_invoice_id }} via {{ $payment_system }}.</p>
	
	<p>Date paid: {{ $date_paid }}</p>
 
 	 <br /><br /><br />
	 <small>Processed via <a href="http://www.sighted.com">sighted.com</a></small>
 
	@stop
	
 