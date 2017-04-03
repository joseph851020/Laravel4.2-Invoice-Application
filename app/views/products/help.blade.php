@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Help &raquo; Products</h1>
 		 
	  
	@stop
	
	@section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Products');				 
				  $('.menu_help_products').addClass('selected');
			  }
		 
		});
		
	</script>
	
@stop