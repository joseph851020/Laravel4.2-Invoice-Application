@extends('layouts.default')

	@section('content')	 
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Payment was successful.</h1>
	 <div id="card_charged_page">
		 
		<p>Your subscription level is: <span><?php echo $newplan; ?></span></p>
		<p><strong>Amount Paid:</strong> &pound;<?php echo $last_history->amount; ?></p>
		<p>See full payment history, <a href=" {{ URL::to('subscription/history') }}">click kere</a></p>
		 
	</div><!-- END card_charged_page -->
 
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