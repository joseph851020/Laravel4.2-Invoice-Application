@extends('layouts.default')

	@section('content')	 
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Payment cancelled</h1>
	<div class="subscription_show" class="group">
	
	<p>Please <a href="http://www.integrityinvoice.com/contact.php" target="_blank">contact us</a> if there was any problem completing your order.</p>
	<p>See full payment history, <a href="{{ URL::to('subscription/history') }}">click here</a></p>
	
	</div>
	<!-- END subscription_show -->
	@stop
	

@section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_subscription').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop