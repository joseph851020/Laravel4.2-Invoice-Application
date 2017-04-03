@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Help &raquo; Merchants</h1>
 		 
	  
	@stop
	
	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
		 
			 if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Expenses');				 
				  $('.menu_all_merchants').addClass('selected');
			  }
			 
		});
		
	</script>
 
	@stop