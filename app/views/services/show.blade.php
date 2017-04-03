@extends('layouts.default')

	@section('content')
 
		<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('services', 'Items', array(), array('class' => '')) }} &raquo; <span>{{ $service->item_name }}</span></h1>
	
		<p>Price: {{ $service->unit_price; }} <br />
		   Tax type: {{ $service->tax_type; }} <br />
		</p>
	@stop