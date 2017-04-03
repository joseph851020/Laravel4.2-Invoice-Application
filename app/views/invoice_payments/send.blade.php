@extends('layouts.default')

	@section('content')	
	
	<?php
	
	use IntegrityInvoice\Utilities\AppHelper as AppHelper;

	$progress_payment_email_sbj = $preferences->progress_payment_message_subject;
	$progress_payment_email = $preferences->progress_payment_message;
	
	// LEGENDS
	$legends = array();
	$legends[0] = '/\b_INVOICE_NUMBER_\b/';
	$legends[1] = '/\b_CLIENT_COMPANY_\b/';
	$legends[2] = '/\b_CLIENT_CONTACT_PERSON_\b/';
	$legends[3] = '/\b_AMOUNT_DUE_\b/';
	$legends[4] = '/\b_DUE_DATE_\b/';
	$legends[5] = '/\b_PAYMENT_TO_DATE_\b/';
	$legends[6] = '/\b_STILL_TO_PAY_\b/';
	$legends[7] = '/\b_SENDER_USER_\b/';
	$legends[8] = '/\b_SENDER_COMPANY_\b/';
	$legends[9] = '/\b_AMOUNT_PART_PAID_\b/';
	$legends[10] = '/\b_PAYMENT_METHOD_\b/';
	$legends[11] = '/\b_INVOICE_WEBPAGE_VIEW_\b/';	
	
	$prex = $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "";
	
	$currencyCode = AppHelper::dumCurrencyCode($invoice->currency_code);
	$currencyCode = AppHelper::escapeRegex($currencyCode);
		
	$replacements = array();
	$replacements[0] = $prex. AppHelper::invoiceId($invoice->tenant_invoice_id);
	$replacements[1] = $client->company;
	$replacements[2] = $client->firstname ." ". $client->lastname;
	$replacements[3] = $currencyCode. "" . number_format($invoice->balance_due, 2, '.', ',');
	$replacements[4] = AppHelper::date_to_text($invoice->due_date, $preferences->date_format);
	$replacements[5] = $currencyCode. "" . number_format($total_paid_todate, 2, '.', ',');
	$replacements[6] = $currencyCode. "" . number_format($invoice->balance_due  - $total_paid_todate, 2, '.', ',');
	$replacements[7] = Session::get('firstname') . ' ' . Session::get('lastname');
	$replacements[8] = $company->company_name;
	$replacements[9] = $currencyCode. "".number_format($this_payment->amount, 2, '.', ',');
	$replacements[10] = $this_payment->payment_method;
	$replacements[11] = $public_url;
	
	$subject_line = preg_replace($legends, $replacements, $progress_payment_email_sbj);
	$email_body = preg_replace($legends, $replacements, $progress_payment_email);
	 
	
	?>
 
<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Invoice <a class="to_all" href="{{ URL::to('invoices/'.$invoice->tenant_invoice_id) }}">{{ $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "" }}{{ Apphelper::invoiceId($invoice->tenant_invoice_id) }} </a> &raquo; <a href="{{ URL::to('payments/'.$invoice->tenant_invoice_id) }}">Payment</a> &raquo; Acknowledgement </h1>

		@if($errors->has())
		<div class="flash error msg_error">
		<ul>
			{{ $errors->first('progress_payment_email_subject', '<li>:message</li>') }}		 
			{{ $errors->first('progress_payment_email_body', '<li>:message</li>') }}
	 
		</ul>
		</div>
		@endif  
 
 {{ Form::open(array('route' => array('payment_acknowledgement_email', $invoice->tenant_invoice_id, $payment_id) , 'method' => 'post', 'class' => '') ) }}	
	<div id="send_invoice_form">
		 		
	    <div class="longbox-left more_space">
	    	
	    	@if($client->email_secondary != "" || $client->email_secondary != NULL)
	    	 <p><input type="checkbox" checked="checked" name="email_primary" class="" value="{{ $client->email }}"><label class="inline">Primary: {{ $client->firstname .' '. $client->lastname }} ({{ $client->email }})</label><br />
	    	 	<input type="checkbox" checked="checked" name="email_secondary" class="" value="{{ $client->email_secondary }}"><label class="inline">Secondary: {{ $client->firstname_secondary .' '. $client->lastname_secondary }} ({{ $client->email_secondary }})</label></p>
	    	@endif
	    	   
             <label>Aknowledgement subject</label>
             <input type="text" class="txt" id="progress_payment_email_subject" name="progress_payment_email_subject" value="<?php echo $subject_line; ?>" />
             <label>Aknowledgement message</label>
             <textarea id="progress_payment_email_body" name="progress_payment_email_body" class="txtarea"><?php echo $email_body; ?></textarea>
             <input type="hidden" name="client_email" value="<?php echo $client->email; ?>" />
             <input type="hidden" name="from_email" value="<?php echo $company->email; ?>" />
             <input type="hidden" name="invoice_id" value="<?php echo $invoice->tenant_invoice_id; ?>" /><br /><br />
              
             <input type="hidden" name="firstname_primary" value="<?php echo $client->firstname; ?>" />
             <input type="hidden" name="firstname_secondary" value="<?php echo $client->firstname_secondary; ?>" />
             <input type="submit" class="gen_btn" id="" name="send_progress" value="Send" />   

	   </div><!-- END longbox -->
  		 
  </div><!-- END  Sending form -->
  
  {{ Form::close() }}

 
	@stop
