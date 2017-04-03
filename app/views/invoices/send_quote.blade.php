@extends('layouts.default')

	@section('content')	
	
	<?php
	
	use IntegrityInvoice\Utilities\AppHelper as AppHelper;

	// We have passed in $invoice from controller
	// We have passed in $client from the controller
	
	//$prep_user = User::find_object_by_sql("SELECT * FROM users WHERE tenantID='".$tenant_id."' AND id=".$invoice->user_id); 
	$invoice_email_sbj = $preferences->quote_send_message_subject;
	$invoice_email = $preferences->quote_send_message;
	
	// LEGENDS
	$legends = array();
	$legends[0] = '/\b_QUOTE_NUMBER_\b/';
	$legends[1] = '/\b_CLIENT_COMPANY_\b/';
	$legends[2] = '/\b_CLIENT_CONTACT_PERSON_\b/';
	$legends[3] = '/\b_AMOUNT_DUE_\b/';
	$legends[4] = '/\b_DUE_DATE_\b/';
	$legends[5] = '/\b_SENDER_USER_\b/';
	$legends[6] = '/\b_SENDER_COMPANY_\b/';
	$legends[7] = '/\b_INVOICE_WEBPAGE_VIEW_\b/';
	
	$prex = $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "";

	$replacements = array();
	$replacements[0] =  $prex. AppHelper::invoiceId($invoice->tenant_quote_id);
	$replacements[1] = $client->company;
	$replacements[2] = $client->firstname ." ". $client->lastname;
	$replacements[3] = AppHelper::dumCurrencyCode($invoice->currency_code). "" . number_format($invoice->balance_due, 2, '.', ',');
	$replacements[4] = AppHelper::date_to_text($invoice->due_date, $preferences->date_format);
	$replacements[5] = Session::get('firstname') . ' ' . Session::get('lastname');
	$replacements[6] = $company->company_name;
	$replacements[7] = $public_url;

	$subject_line = preg_replace($legends, $replacements, $invoice_email_sbj);
	$email_body = preg_replace($legends, $replacements, $invoice_email);
	
	/////////// Thank you
	//$thank_you_subject = preg_replace($legends, $replacements, $preferences->thank_you_message_subject);
	//$thank_you_body = preg_replace($legends, $replacements, $preferences->thank_you_message);
	
	?>
    

<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Sending &raquo; <a href="{{ URL::previous() }}">Quotes </a> &raquo; <a class="to_all" href="{{ URL::to('quotes/'.$invoice->tenant_quote_id) }}">{{ $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "" }}{{ Apphelper::invoiceId($invoice->tenant_quote_id) }} </a></h1>

		@if($errors->has())
		<div class="flash error msg_error">
		<ul>
			{{ $errors->first('invoice_email_subject', '<li>:message</li>') }}		 
			{{ $errors->first('invoice_email_body', '<li>:message</li>') }}
	 
		</ul>
		</div>
		@endif  
 
 
 {{ Form::open(array('route' => array('email_quote', $invoice->tenant_quote_id) , 'method' => 'post', 'class' => '') ) }}	
	<div id="send_invoice_form">
		 		
	    <div class="longbox-left more_space">
	    	
	    	@if($client->email_secondary != "" || $client->email_secondary != NULL)
	    	 	<p><input type="checkbox" checked="checked" name="email_primary" class="" value="{{ $client->email }}"><label class="inline">Primary: {{ $client->firstname .' '. $client->lastname }} ({{ $client->email }})</label><br />
	    	 	<input type="checkbox" checked="checked" name="email_secondary" class="" value="{{ $client->email_secondary }}"><label class="inline">Secondary: {{ $client->firstname_secondary .' '. $client->lastname_secondary }} ({{ $client->email_secondary }})</label></p>
	    	@endif
	    	
	             <label>Subject</label>
	             <input type="text" class="txt" id="inv_email_subject" name="invoice_email_subject" value="<?php echo $subject_line; ?>" />
	             <label>Body</label>
	             <textarea id="inv_email_body" name="invoice_email_body" class="txtarea"><?php echo $email_body; ?></textarea>
	             <input type="hidden" name="client_email" value="<?php echo $client->email; ?>" />
	             <input type="hidden" name="from_email" value="<?php echo $company->email; ?>" />
	             <input type="hidden" name="invoice_id" value="<?php echo $invoice->tenant_quote_id; ?>" /><br /><br />
	             
	             <input type="hidden" name="firstname_primary" value="<?php echo $client->firstname; ?>" />
	             <input type="hidden" name="firstname_secondary" value="<?php echo $client->firstname_secondary; ?>" />
	             <input type="submit" class="gen_btn" id="" name="send_quote" value="Send" />

	   </div><!-- END longbox -->
  		<!--
	   <div class="longbox-right">
	   
	    </div><!-- END longbox -->
  </div><!-- END  Sending form -->

  {{ Form::close() }}

 
	@stop
	
	@section('footer')
	
	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
  				// Check if ULR Contain invoice
  				if(window.location.href.indexOf("invoice") > -1){
  					
  					$('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_invoices').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
  					 
			  		
  				}else if(window.location.href.indexOf("quote") > -1){
  					
  					$('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_quotes').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			   		
  				}				   
		     }
		 
		});
		
	</script>
 
	@stop
