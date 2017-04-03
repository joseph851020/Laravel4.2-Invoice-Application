@extends('layouts.sendmail')

	@section('email_title')
	 <h3> {{ $email_subject }}</h3>
	@stop

	@section('content')
		{{ $email_body }}
	 
	@stop