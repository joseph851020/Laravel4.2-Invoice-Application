@extends('layouts.sendinvoice')

	@section('email_title')
	 <h3> {{ $inv_email_subject }}</h3>
	@stop

	@section('content')
	 {{ $inv_email_body }}
	 
	 <br /><br />
	 <small>Sent via <a href="http://www.sighted.com">sighted.com</a></small>
	@stop
