@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; Glossary of Terms</h1>
	
	<div id="quick_start">
		 
		 <div class="guide_section">
		 
			<h4>A - Z meaning of common terms you may come across in the system. </h4><p>Some of the definitions comes from Businesslink.com, Wikipedia.com, Thefreedictionary.com and the Oxford dictionary.</p>
	    	
	    	<p class="a_z_term">Client (Customer)</p>
	        <p class="a_z_meaning">A person who uses the services or advice of a professional person or organization.</p>
	        <p class="a_z_term">Company profile</p>
	        <p class="a_z_meaning">The details such as company address, email, telephone number and website of your company or business.</p>
	        <p class="a_z_term">Dashboard</p>
	        <p class="a_z_meaning">The screen or page where you see various system usage analytics and run reports where possible.</p>
	        <p class="a_z_term">Discount</p>
	        <p class="a_z_meaning">A reduction from the full or standard amount of a price on an invoice.</p>
	        <p class="a_z_term">Due date</p>
	        <p class="a_z_meaning">The latest date which full payment for an invoice should be received. You can set it as immediate i.e. upon receipt, 1 week, 2 weeks or 1 month.</p>
	        <p class="a_z_term">Footer</p>
	        <p class="a_z_meaning">The bottom part of an invoice. You can customize the footer text in the default settings with a message like ‘thanks for your business’.</p>
	        <p class="a_z_term">Invoice</p>
	        <p class="a_z_meaning">A bill for goods that somebody has bought or work that has been done for somebody</p>
	       	<p class="a_z_term">Logo</p>
	        <p class="a_z_meaning">This is your business / company brand artwork that goes on your stationery such as business cards.</p>
	        <p class="a_z_term">Recurring</p>
	        <p class="a_z_meaning">Something that happens again or a number of times e.g. the same invoice which your company issues weekly or monthly to a particular client.</p>       
	        <p class="a_z_term">Reminder Email</p>
	        <p class="a_z_meaning">An email sent to your client to remind them of payment which is due on a particular invoice. This can be set to go out 3 days or 7 days after the due date on an invoice.</p>
	        <p class="a_z_term">Thank you email</p>
	        <p class="a_z_meaning">An email sent to thank your client when they have made full payment of the due invoice.</p>        
	        <p class="a_z_term">Template</p>
	        <p class="a_z_meaning">The layout of a PDF invoice as displayed on screen or when printed.</p>
	        <p class="a_z_term">User / Staff </p>
	        <p class="a_z_meaning">The administrator who uses the system to create and manage items, clients or invoices.</p>
	        <p class="a_z_term">VAT</p>
	        <p class="a_z_meaning">VAT is a tax that's charged on most business transactions in the UK. 
	        	Businesses add VAT to the price they charge when they provide goods and services to business and non-business customers. 
	        	You can set the default tax value for each item.</p>
					 
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