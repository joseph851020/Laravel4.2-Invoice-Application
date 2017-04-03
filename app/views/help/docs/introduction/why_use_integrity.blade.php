@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; Why Use Sighted?</h1>
	
	<div id="quick_start">
		 
		 <div class="guide_section">

             <p>Not sure how to keep track of every little outflow?</p>
             <p> Tired of the rigorous process of making and sorting through paper, email, word or excel invoice formats?</p>
             <p>Want to improve cash-flow while eliminating manual operations?</p>
             <p>Need to create professional invoices and receipts online, in any currency and avail the facility of online payments and receipts?</p>

		 	<p>Sighted lets you gain complete control over all your business cash-flow issues.</p>

             <p> Paper-based invoicing systems can be clunky, inconvenient and inefficient, but commercial invoicing and financial tracking software solutions can be very expensive, hard to use and they're often packed with tons of features that the average entrepreneur, freelancer or small business owner just doesn't need!</p>

			<p>What's more, paper-based and software-based invoicing and receipt systems make it impossible to manage your financials from multiple devices and remote locations. So if you need to issue a receipt from home, but your software program is at the office, you're simply out of luck!</p>
			
			<p>But now, there's a new, web-based solution that's designed specifically for small businesses, freelancers, entrepreneurs and beyond. Sighted is designed with a range of user-friendly features that make the invoicing and expense tracking process fast, simple and convenient!</p>
					 
		    <p>Sighted Invoice...Easy. Fast. Secure. Effective.</p>
					 
		</div><!-- END Quide section -->
  
	</div><!-- END QUICK START-->
 
 		 
	@stop
	

@section('footer')

	<script>
	
		$(function(){
		 
		 	 if($('#appmenu').length > 0){
				    
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_help').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop