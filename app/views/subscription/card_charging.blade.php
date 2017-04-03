@extends('layouts.default')

	@section('content')	 
	
	<h1>Charge</h1>
 
 	<div id="card_page">	 
	     
	      <a class="btn" href="{{ URL::previous() }}">Back</a>
	</div><!-- END history_page -->
 	
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

	 <script src="https://js.stripe.com/v2/"></script>
	 <script src="{{ URL::asset('assets/js/billing.js') }}"></script>
	
@stop