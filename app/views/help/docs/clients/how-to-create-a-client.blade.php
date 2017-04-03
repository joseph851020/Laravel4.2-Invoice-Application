@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; How to create a client</h1>
	
	<div id="quick_start">
		 
		 <div class="guide_section help_section">
		 	
		 	<div class="quick_start_video vid_in_help">				 
				<iframe src="//player.vimeo.com/video/102749286" width="561" height="315" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> 
			</div>
			 
			 <h2>Text instruction</h2>
			 
			 <ol class="steps">
			 	<li>Open the Create Tab from the navigation menu</li>
			 	<li>Click <span>Create Client</span></li>
			 	<li>Enter the Company or Business name of your client</li>
			 	<li>If you’d like to enter address details just click on show address and other fields and fill in the details</li>
			 	<li>Enter the Primary contact details such as first name, last name, email and telephone</li>
			 	<li>If you wish to add more than one contact details for the client i.e. secondary contact. Click on add secondary contact link. And fill in the details</li>
			 	<li>Finally, click on create button to save the client</li>
			 	 
			 </ol>
			 
			 <p> Your client list is available when creating an invoice or a quote. <br />Note: You can also create a new client on the fly when creating an invoice.</p>
			 
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