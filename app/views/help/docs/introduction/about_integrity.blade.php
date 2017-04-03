@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; About Sighted</h1>
	
	<div id="quick_start">
		 
		 <div class="guide_section">
			<div class=""><img width="100" height="100" src=" {{ URL::asset('integritylogo.png') }}" alt="Sighted Invoice" /></div>
             <p>&nbsp;</p>
             <h2>At glance</h2>

			 <p>Sighted is duly registered in USA as a private company that offers software services with registered office in Palo Alto California, United States.<p>
             <p>Sighted is a simple online invoicing and expense tracking application, customized for freelancers and solo-entrepreneur.</p>
			 <p><strong>Our mission</strong> is to ensure that you GET PAID faster and easier than ever before. </p>


             <h2>How it all started</h2>
             <p>In 2016, Murray Newlands, the founder of Sighted decided that enough was enough. He was spending too much time sorting through emails, work documents and Excel files to keep track of the incomings and outgoings of the extremely complex financial side of a freelancer’s life.</p>
             <p>Moses, a freelance web and graphic designer, decided to create an invoice tracking and expense tracking application for freelancers like him and other solo entrepreneurs. Most of these types of software were hard to use, chunky, packed with features that most solo entrepreneurs would never use, inciting Moses to work on creating an affordable piece of software with the end user (freelancers and solo entrepreneurs) in mind.</p>
             <p>After looking around for a little while and questioning some of his clients and colleagues, it became obvious to Moses that there was no such solution available. Every one he spoke to was frustrated with their finances and were unable to keep track of things. This lead to some serious issues with registered businesses and personal lives when trying to file accounts with the company house and HMRC.</p>
             <p>Moses then started to use his creative and innovative skills to invent an easy-to-use and effective invoicing and expense tracking software that is feature rich and not clunky. He wanted to finally solve the problem for solo entrepreneurs in their desperate search of a financial tracking solution. With a lot of hard work designing the application for the end user, and many discussions with clients and colleagues about the functionality and setup, Sighted was finally born.</p>
             <p>Sighted now helps freelancers and entrepreneurs save time by simplifying their financial world so they don’t have to worry or feel concerned and keep on top of things. Now they can enjoy being paid faster and easier than before while improving cash flow and giving easy access to integrated online payments.</p>

		 
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