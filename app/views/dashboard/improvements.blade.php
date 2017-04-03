@extends('layouts.default')

	@section('content')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Recent updates</h1>
		<div id="improvements">
			
		<div id="improvement">
			<h4 class="release_date">July 2014 Version 2.0 updates</h4>	
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Brand new clean, minimalistic, and responsive layout</span>
				<span class="update_details">With support for mobile such as tablets and smart phones</span>
			</p>
		 
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Integration of XE currency symbols </span>
				<span class="update_details">(e.g. $, £, ₦, €, ¥ and many more)</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Introduction of Quote / Estimate</span>
				<span class="update_details">useful when you need to send out a fixed price offer or an educated guess at what a product or service might cost. Once accepted by your client, you can later convert this into an invoice with just one click of a button.</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Business model setup</span>
				<span class="update_details">you can now configure the system based on your unique offerings e.g. as a service provider or product seller, and if you provide services you can define precisely how you bill your customers e.g. per project or per hour.</span>
			</p>
		  
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Alternative contact person on client side</span>
				<span class="update_details">Add more than one representative (client contact person) </span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Download invoices and expenses as CSV files (Excel) for further analysis</span>
				<span class="update_details">Previously only clients and products can be download</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Prefix invoice and quote number with custom characters </span>
				<span class="update_details">For example MX00002</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Add cheque number to invoice payment made by cheque</span>
				<span class="update_details">and even bank transfer ref. number to invoice payment made by online transfer</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Real time invoice status with appropriate sticker e.g. PAID, PART PAID.</span>
				<span class="update_details">If an invoice is part paid, details of how much has been received and how much is outstanding are also shown on the invoice. With these, you don’t need to worry about sending a receipt to your clients, instead, simply send an updated invoice with a single click. </span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Mark invoice as paid</span>
				<span class="update_details">This will also record the full payment behind the scenes</span>
			</p>
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Enable or disable discount and tax columns on individual invoices</span>
				<span class="update_details">This removes unrequired columns in the invoice,making your invoice much cleaner</span>
			</p>
			
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Items have been broken down into products and services</span>
				<span class="update_details">This makes it easier to manage your products and services and use on invoice line / row respectively</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Context aware invoice table headers</span>
				<span class="update_details">Depending on your offering, invoice table headers will display appropiate labels e.g. Product invoice will show Amount and Quantity, whereas Service invoice will show Rate and hour(s). If you charge per project the hour(s) does not apply. </span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Set option to show or hide bank / payment details on individual invoices</span>
				<span class="update_details">This is very useful if you don't want your bank / payment details to appear on some invoices</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Client are now able to view an updated invoice online, make payment and download PDF</span>
				<span class="update_details">When a client makes a payment, and you send acknowledgement via the system, the system will send an email with a link for the client to download or view an updated invoice with updated payment status, changes, and even see when the invoice was last updated by you.</span>
			</p>
			  
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;New and updated invoice template layouts</span>
				<span class="update_details">Best part is we are constantly making them better. If you have a particular design in mind; just send us the PDF or JPG file example and we will integrate it ASAP</span>
			</p>
			 
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Expanded list of expense categories</span>
				<span class="update_details">This list now cover pretty much every area of expense you might want to record</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Accept debit or credit card payment on your invoices </span>
				<span class="update_details">Stripe API integration, see more at www.stripe.com</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;New HTML email design / template for sending your invoices </span>
				<span class="update_details">Previously, emails were sent using plain text, now your email messages are formatted in nice HTML template, making it look more professional.</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;New reporting tool</span>
				<span class="update_details">With new capabilities, enabling you to see your profit and loss for the last 7 days, one month, 3 months and one year</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Complete redesign of user interface elements </span>
				<span class="update_details">From new input styling to nicer colours</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;New dashboard quick account summary</span>
				<span class="update_details">See better data at a glance</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Logo auto resize and in any format PNG, JPG</span>
				<span class="update_details">Now you can upload your logo in any size, the system will automatically adjust / resize to the ideal size. </span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;More convenient ways to pay for your account subscription</span>
				<span class="update_details">Pay for your Integrity Invoice subscription using your debit or credit card, although we still support PayPal</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Hundreds of bug fixes</span>
				<span class="update_details">This makes the application a whole lot more efficient, powerful, faster and reliable</span>
			</p>
			 
			
		</div><!-- END Improvement -->
		
		<div id="improvement">
			
			<h4 class="release_date">April 2014 updates</h4>	
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Choose a different theme for your account</span>
				<span class="update_details">Now you can define how you want your account interface to look by selecting from a list of predefined themes.</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Improved report centre</span>
				<span class="update_details">You can now filter with better options based on invoice status</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Client view invoice online</span>
				<span class="update_details">In addition to PDF invoices, Integrity Invoice now let your client click a link in an email which opens up HTML like view of the invoice, they can choose to pay from there too.</span>
			</p>
			
		</div><!-- END Improvement -->
		
		  
		<div id="improvement">
			<h4 class="release_date">December 2013 updates</h4>	
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Import Items via CSV</span>
				<span class="update_details">This let you create multiple items at once, based on the data in the CSV Excel file</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Import Clients via CSV</span>
				<span class="update_details">This let you create multiple clients at once, based on the data in the CSV Excel file</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Add individual line item tax</span>
				<span class="update_details">You will be able to choose between type of taxes.</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Add individual line item discount</span>
				<span class="update_details"> This is now possible with percentage and flat value.</span>
			</p>
			
		  </div><!-- END Improvement -->
		  
		 
		<div id="improvements">
			
			<div id="improvement">
			<h4 class="release_date">02/06/2013 updates</h4>	
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Added Paypal payment gateway</span>
				<span class="update_details">You can now receive direct online payment on your invoices using paypal.</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Zip all invoice in a date range</span>
				<span class="update_details">It is now possible to zip invoices and download them for other purposes.</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp; Record part payments on invoices</span>
				<span class="update_details">With this you can now record several part payments and send acknowledgement emails.</span>
			</p>
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Support for month/day/year date format</span>
				<span class="update_details">We now support US like date format e.g. 01/31/2011.</span>
			</p>
			
			
			<p>
				<span class="update_title"> <i class=" fa fa-check-square-o"> </i>&nbsp;Recurring option added</span>
				<span class="update_details">You can now auto send invoices to your clients based on selected frequencies.</span>
			</p>
			
		  </div><!-- END Improvement -->
		  
		</div><!-- END Improvements -->
		
	@stop
	

	@section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_improvements').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop