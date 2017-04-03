@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; Frequently Asked Questions</h1>
	
	<div id="quick_start">
		 
		 <div class="guide_section">

             <p class="a_z_term">Can I switch or upgrade at anytime?</p>
             <p>Yes. Upgrading or downgrading is very easy. You can do it at any time.</p>

             <p class="a_z_term">Do I need more than one user?</p>
             <p>No you don’t unless you want another person to have access to your account. The Super premium lets you add more than one user.</p>

             <p class="a_z_term">Are there any long-term commitments or cancellation fees?</p>
             <p>No. You can cancel your account at anytime within the system.</p>

             <p class="a_z_term">What do you mean by multi currency?</p>
             <p>This means you can invoice your clients in any currency and record your expenses in any currency. Our system offers a currency exchange rate feature.</p>

             <p class="a_z_term">Is it possible to import or export data into my Sighted Invoice account?</p>
             <p>Yes, you can import data from Excel CSV file for your clients and items (products and services). A sample format is provided within the system, and if you need any help we're always happy to assist.</p>

             <p class="a_z_term">Can I put my company logo and information into the invoice?</p>
             <p>Yes, you can upload your company logo as a PNG file 250px wide and 100px high. If you need assistance in making your logo fit these dimensions, let us know and we can help.</p>

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