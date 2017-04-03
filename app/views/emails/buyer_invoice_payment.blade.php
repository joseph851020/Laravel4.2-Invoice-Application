@extends('layouts.payment_notification')
 
	@section('content')
	  
	<p>Hello, <p/>
 
	<p>This email is a confirmation of your payment on Invoice {{ $tenant_invoice_id }} to {{ $company_name }}.</p>
	
	<p>If you have queries please contact: {{ $company_email }}</p>
 
 	 <br /><br /><br />
	 <small>Processed via <a href="http://www.sighted.com">sighted.com</a></small>
 
	@stop
 