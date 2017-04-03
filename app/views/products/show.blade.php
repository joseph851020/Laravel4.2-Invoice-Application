@extends('layouts.default')

	@section('content')
 
		<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('products', 'Items', array(), array('class' => '')) }} &raquo; <span>{{ $product->item_name }}</span></h1>
	
		<p>Price: {{ $product->unit_price; }} <br />
		   Tax type: {{ $product->tax_type; }} <br />
		</p>
	@stop