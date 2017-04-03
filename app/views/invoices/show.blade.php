@extends('layouts.default')

	@section('content')
	
	<?php
	
	use IntegrityInvoice\Utilities\AppHelper as AppHelper;
	// Get preferences
	// We have passed in $preferences from the controller
	// We have passed in $invoice from controller
	// We have passed in $company from controller
	// We have passed in $client from the controller

	$lineitems = explode("|", $invoice->items);
 
	$line_item_total = count($lineitems);
	$items = array();
	
	for($i=0; $i < $line_item_total; $i++){
		
	 //${'item_' . $i}[] = explode(',',$lineitems[$i]);
	 $items[] = explode(',',$lineitems[$i]);
	 // remove empty value
	 ($items[$i][0] == ' ') ? array_shift($items[$i]) : "";
	  (count($items[$i]) == 1) ? array_pop($items) : "";
	
		
	}
	//echo $item_0[0][0];
	//echo $items[1];
	
	// replacement for comma
	 $find ="/__/"; 
 	 $replace =","; 
	 
	 
	 // Currency
	 $cur = $invoice->cur;
	 
	 $template_id = $preferences->invoice_template;
	 
	 if($request_type == 'invoice')
	 {
	 	$l_id = $invoice->tenant_invoice_id;
	 }
	 else
	 {
		 $l_id = $invoice->tenant_quote_id;
	 }
	 
	 
	 $enable_discount = true;
	 $enable_tax = true;
	  
	  if($invoice->enable_discount == 0) { $enable_discount = false; }else{ $enable_discount = true;}
      if($invoice->enable_tax == 0) { $enable_tax = false; }else{ $enable_tax = true;}
	  
	  // Default with discount and no tax
	  $colspan1 = $invoice->business_model == 0 ? 1 : 3;
	  $colspan2 = $invoice->business_model == 0 ? 4 : 5;
 
	  
	  // Only Discount enabled
	  if($enable_discount == true && $enable_tax == false)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 3;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 3 : 1;
		  
		  if($invoice->business_model == 1 && $invoice->bill_option == 1){
		  	 $colspan1 = 2;
			 $colspan2 = 1;
		  }	
		  
	  }
	  
	  // Only Tax enabled
	  if($enable_discount  == false && $enable_tax  == true)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 3;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 3 : 1;	
		  
		  if($invoice->business_model == 1 && $invoice->bill_option == 1){
		  	 $colspan1 = 2;
			 $colspan2 = 1;
		  }	   
	  }
	  
	   // Discount and tax enabled
	  if($enable_discount  == true && $enable_tax  == true)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 2;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 4 : 3;
		  
		  if($invoice->business_model == 1 && $invoice->bill_option == 1){
		  	 $colspan1 = 3;
			 $colspan2 = 1;
		  }	
		 
	  }
	  
	  // Discount and tax disabled
	  if($enable_discount  == false && $enable_tax  == false)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 2 : 2;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 0;
		  
		  if($invoice->business_model == 1 && $invoice->bill_option == 1){
		  	 $colspan1 = 1;
			 $colspan2 = 1;
		  }
		   
	  }
	 
	?>

	@if($request_type == 'quote')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('quotes', 'Quotes', array(), array('class' => 'to_all')) }} &raquo;  {{ $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "" }}<span><?php echo AppHelper::quoteId($l_id); ?></span></h1>
	@elseif($request_type == 'invoice')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('invoices', 'Invoices', array(), array('class' => 'to_all')) }} &raquo; {{ $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "" }}<span><?php echo AppHelper::invoiceId($l_id); ?></span>
        @if($invoice->recurring == 1)
        <span><i class="fa fa-repeat"></i> Recurring
            @if($invoice->recur_status == 1)
                <span class="makeGreen">active</span>
             - next billing date: <?php $next_recur_date = $invoice->recur_next_date;
            echo AppHelper::date_to_text($next_recur_date, $preferences->date_format);?></span>
            @else
                <span class="makeRed">inactive</span>
            @endif

        @endif</h1>
	@endif
	
	
	@if($errors->has())
  	  <div class="flash error">
		<ul>
			{{ $errors->first('file', '<li>:message</li>') }}
		</ul>
	  </div>
	@endif 
	 
	<div class='action-buttons'>
		<a href="{{ URL::to($request_type.'s/'.$l_id.'/download') }}" class="gen_btn btn_light generate_invoice" 
			tenant_id="<?php echo $invoice->tenantID; ?>" id="<?php echo $invoice->tenant_invoice_id; ?>" title="Download Invoice PDF">PDF</a>
		<a href="{{ URL::to($request_type.'s/'.$l_id.'/send') }}" id="sendtoclient" class="gen_btn btn_light"><?php echo $invoice->status == 0 ? "Send" : "Resend"; ?></a>	
	  
		@if($request_type == 'invoice')
		
		@if($invoice->payment < 2)
		<a href="{{ URL::to('payments/'.$invoice->tenant_invoice_id.'/paid') }}"  class="gen_btn btn_light markaspaid_popup_open" tInvId="{{ $invoice->tenant_invoice_id }}">Mark as paid</a>
		@endif
		
		@if($invoice->payment == 2)
			<a href="{{ URL::to('payments/'.$invoice->tenant_invoice_id.'/send_receipt/1') }}" class="gen_btn btn_light" title="Download Receipt PDF">Download Receipt</a>			
		@endif
		
		<a href="{{ URL::to('payments/'.$invoice->tenant_invoice_id) }}"  class="gen_btn btn_light">Payments</a>
		
		@elseif($request_type == 'quote')
		<a href="{{ URL::to('quotes/'.$invoice->tenant_quote_id.'/convert') }}"  class="gen_btn btn_light">Convert to invoice</a>
		@endif
		
		@if($request_type == 'invoice')
		<a href="#" id="copyinvoice" class="gen_btn btn_light attachment_popup_open <?php echo $invoice->file != NULL ? 'makeDisabled' : ''; ?>"><i class="fa fa-plus"></i> Attach file</a>
		@endif
		<a href="{{ URL::to($request_type.'s/'.$l_id.'/copy') }}" id="copyinvoice" class="gen_btn btn_light">Copy</a>
		
		<?php if($invoice->status != 3): ?>
			<a href="{{ URL::to($request_type.'s/'.$l_id.'/edit') }}" class="gen_btn btn_light">Edit</a>
		<?php endif;?>
		
		
		<a href="{{ URL::to($request_type.'s/'.$l_id.'/delete') }}" id="{{ $request_type == 'invoice' ? 'deleteinvoice' : 'deletequote' }}" tno="<?php echo $l_id; ?>" class="gen_btn btn_light">Delete</a>
		
		<input type="hidden" class="template_id" value="<?php echo $template_id; ?>" />
		
	</div><!-- END ACTION BUTTONS -->

 
<div id="invoicecontainer">
	
	<div class="pdf_logo_up">
     <?php   
	     function addhttp($url) {
		    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		        $url = "http://" . $url;
			    }
			    return $url;
		 }
     ?>
	 
	<?php  $tenantID = Session::get('tenantID'); $ext = '.png'; $logo_file =  Config::get('app.app_main_domain'). '/te_da/'.$tenantID . '/'.$tenantID.$ext; ?>
			 
			@if (file_exists($logo_file))					 
				 <img src="{{ Config::get('app.app_main_domain').'/te_da/'.$tenantID.'/'.$tenantID.'.png' }}" alt="" />
			@endif	
	</div><!-- END -->
	
		
	
<input type="hidden" name="date_format" class="date_format" value="<?php echo $preferences->date_format; ?>" />
 
	<div id="identity" class="company_info">		
        <div class="cw" id="address"><p><strong class="cname"><?php echo $company->company_name; ?></strong><br /><?php echo $company->add_1; ?><?php
		if($company->add_2 == ""){
			echo "";
		}else if($company->add_2 == $company->state){
			echo "";
		}else{ echo ", ".$company->add_2 . ", ";} ?> <?php echo $company->state; ?><?php echo "  ".$company->postal_code; ?> 
		<?php echo ". Tel. ". $company->phone; ?></p></div>
      
	</div> <!-- END identity -->
	
	
	
	<div class="invoiceTop">
		
		<div class="invoiceTopLeft">
		
	 	<div id="customer" class="invoice_customer">
	 		 <h2 id="customer-title_show">To: <?php echo $invoice->client_name; ?></h2><p><?php echo $client->add_1 != "" ? $client->add_1 . ", <br />": ""; ?>
            <?php echo $client->add_2 != "" ? $client->add_2 . "<br />": ""; ?>
            <?php echo $client->state != "" ? $client->state . ", ": ""; ?>
            <?php echo $client->postal != "" ? $client->postal . "<br />": ""; ?>
            <?php echo $client->country != "" ? $client->country . "<br />": ""; ?></p>
		</div> <!-- END customer -->
	 
		
		 <!--<div class="invoice_stat">
	
		<?php if($invoice->status == 1): ?>
			<img src="{{ URL::asset('assets/img/icons/status_sent.png') }}" alt="Invoice sent" title="Invoice sent" />
		<?php else: ?>
			<img src="{{ URL::asset('assets/img/icons/status_notsent.png') }}" alt="Draft" title="Draft" />
		<?php endif; ?>
		 
		</div><!-- END Invoice Stats -->

       </div> <!-- END invoiceTopLeft --> 
       
       
       <?php if($request_type == "invoice"): ?>
			
			   @if($invoice->payment == 1)
		       <div class="paidstamp_web">
		       		<img src="{{ Config::get('app.app_domain').'/assets/img/stamp/part_paid1.png' }}">
		       		<br /><h4 class="partpaid_amount"><?php echo AppHelper::dumCurrencyCode($invoice->currency_code). number_format($part_paid_amount, 2, '.', ','); ?> received.</h4>
		       		<h5 class="stilltopay_amount">Outstanding: <?php echo AppHelper::dumCurrencyCode($invoice->currency_code). number_format($invoice->balance_due - $part_paid_amount, 2, '.', ','); ?></h5>
		       </div><!-- END paidstamp -->
		       @endif
		       
		       @if($invoice->payment == 0 && $invoice->status == 0)
		       <div class="paidstamp_web">
		       		<img src="{{ Config::get('app.app_domain').'/assets/img/stamp/draft.jpg' }}">		       		 
		       </div><!-- END paidstamp -->
		       @endif
		       
		       @if($invoice->payment == 2)
		       <div class="paidstamp_web">
		       		<img src="{{ Config::get('app.app_domain').'/assets/img/stamp/paid1.png' }}">
		       </div><!-- END paidstamp -->
		       @endif
			
		<?php endif; ?>
		
       
      
        <div class="invoiceTopRight" id="invnum">
        	<table id="meta">
                <tr>
                    <td class="meta-head">@if($request_type == 'quote') 
                    						{{ 'Quote ID' }}
                    						@elseif($request_type == 'invoice')
                    						{{ 'Invoice ID' }}
                    						@endif</td>
                    <td><div class="cw inv_num">{{ $preferences->invoice_prefix != "" ? $preferences->invoice_prefix : "" }}<?php echo AppHelper::invoiceId($l_id); ?></div></td>
                </tr>
                <tr>
                    <td class="meta-head">Issue Date</td>
                    <td><div><?php $inv_date = $invoice->created_at;
					 echo AppHelper::date_to_text($inv_date, $preferences->date_format);?></div>
					 </td>
                </tr>
                <tr>
                    <td class="meta-head">@if($request_type == 'quote') 
                    						{{ 'Amount' }}
                    						@elseif($request_type == 'invoice')
                    						{{ 'Amount Due' }}
                    						@endif</td>
                    <td><div><?php echo AppHelper::dumCurrencyCode($invoice->currency_code). number_format($invoice->balance_due, 2, '.', ','); ?></div></td>
                </tr>
                @if($request_type == 'invoice') 
                <tr>
                    <td class="meta-head">Due Date</td>
                    <td><div><?php $inv_date = $invoice->due_date;
					 echo AppHelper::date_to_text($inv_date, $preferences->date_format);?></div></td>
                </tr>
                @endif
            </table>
            
		</div> <!-- END invnum -->
 
 </div><!-- End Invoice Top -->

 
	<?php $dateformat ="d/m/Y";
		// British dateformat
		if($preferences->date_format == "dd/mm/yyyy"){ $dateformat ="d/m/Y"; }
		
		// America dateformat
		if($preferences->date_format == "mm/dd/yyyy"){ $dateformat = "m/d/Y"; }	?>	
    	<input type="hidden" class="pref_dateformat" value="{{ $dateformat }}" >
		<input type="hidden" name="pref_tax1" id="pref_tax1" value="<?php echo $preferences->tax_perc1; ?>" tax_1name="<?php echo $preferences->tax_1name; ?>" />
		<input type="hidden" name="pref_tax2" id="pref_tax2" value="<?php echo $preferences->tax_perc2; ?>" tax_2name="<?php echo $preferences->tax_2name; ?>" />
		<table id="items">
		
		  <tr>
		      @if($invoice->business_model == 0)
		      <th width="30%" class="alignLeft">Product</th>
		      @elseif($invoice->business_model == 1)
		      <th width="30%" class="alignLeft">Service</th>
		      @endif	

		      @if($invoice->business_model == 1 && $invoice->bill_option == 0)		      
		      <th width="10%" class="">Rate</th>
		      @else
		      <th width="10%" class="">Cost</th>
		      @endif

		      @if($invoice->business_model == 0)
		      <th width="7%">Qty</th>
		      @elseif($invoice->business_model == 1 && $invoice->bill_option == 0)
		      <th width="7%">Hour(s)</th>
		      @endif

		      @if($invoice->enable_discount)
		      <th width="14%">Discount</th>
		      @endif
		      
		      @if($invoice->enable_tax)
		      <th width="10%">Tax</th>
		      @endif	  
		      	      
		      <th width="15%">Line Total</th>
		  </tr>          
           
          <?php $row = 2; foreach($items as $item): ?>
          <?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
		  <tr class="item-row <?php echo $colour; ?>">
		    
		      <td class="description alignLeft"><?php echo preg_replace($find, $replace, $item[1]); ?></td>
		      <td><?php echo AppHelper::dumCurrencyCode($invoice->currency_code); ?><?php echo number_format($item[2], 2, '.', ','); ?></td>
		      
		      @if($invoice->business_model == 0)
		      <td><?php echo $item[3] ?></td>
		      @elseif($invoice->business_model == 1 && $invoice->bill_option == 0)
		      <td><?php echo $item[3] ?></td>
		      @endif
		      
		      @if($invoice->enable_discount)
		      <td><?php echo AppHelper::get_discount_type($item[6], $item[7], $item[2], $item[3]); ?></td>
		      @endif
		      @if($invoice->enable_tax)
		      <td><?php echo AppHelper::get_tax_type(trim($item[4]),$preferences->tax_perc1, $preferences->tax_perc2); ?></td>  
		      @endif 
			  <td><span class="cur_symbol"><?php echo AppHelper::dumCurrencyCode($invoice->currency_code); ?></span><span class="price"><?php echo $item[8]; ?></span></td>
		  </tr>
           <?php endforeach; ?>
 
		  <tr>
		      <td colspan="{{ $colspan1 }}" class="blank "><h5 class="invoice_note_title">Note / Payment Terms</h5><div class="fornote"><textarea style="height:120px; width:98%;" class="cwl notetext"><?php echo $invoice->note; ?></textarea></div></td>
		      <td colspan="{{ $colspan2 }}" class="total-line"><strong>Sub Total</strong></td>
		      <td class="total-value"><div><span class="cur_symbol"><?php echo AppHelper::dumCurrencyCode($invoice->currency_code); ?></span><span class="c_subtotal" id="subtotal"><?php echo number_format($invoice->subtotal, 2, '.', ','); ?></span></div></td>
		  </tr>
		  
		  @if($invoice->enable_discount == 1)
           <tr id="mydiscount">
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="vat-line"><strong>Discount</strong></td>
		      <td class="vat-value"><div><span class="cur_symbol"><?php echo AppHelper::dumCurrencyCode($invoice->currency_code); ?></span><span id="discount" class="c_discount"><?php echo  number_format($invoice->discount_val, 2, '.', ','); ?></span></div></td>
		  </tr>
		  @endif
		  
		  @if($invoice->enable_tax == 1)
          <tr id="mytax">
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="vat-line"><strong>Tax </strong></td>
		      <td class="vat-value"><div><span class="cur_symbol"><?php echo AppHelper::dumCurrencyCode($invoice->currency_code); ?></span><span id="vat" class="c_vat"><?php echo number_format($invoice->tax_val, 2, '.', ','); ?></span></div></td>
		  </tr>		  
		  @endif
		   
		  <tr>
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="total-line balance">NET Total </td>
		      <td class="total-value balance"><div><span class="cur_symbol"><?php echo AppHelper::dumCurrencyCode($invoice->currency_code); ?></span><span class="due"><?php echo number_format($invoice->balance_due, 2, '.', ','); ?></span></div></td>
		  </tr>
		  
		  <tr>		  	
		  	<td colspan="{{ $colspan1 }}">
		  		<p class="bankinfo"><br />			 
				 <label>Show payment details on PDF</label> <input disabled="disabled" type="checkbox" value="<?php echo $invoice->bankinfo; ?>" <?php if($invoice->bankinfo != 0){ echo "checked=\"checked\""; } ?> name="bankinfo" id="bankinfo" class="">
				<br /><br />
				@if($invoice->file != NULL && $invoice->file != "")
				 <strong>File attachment: </strong><a class="ordinary_link2" href="{{ URL::to('invoices/'.$invoice->tenant_invoice_id.'/download_file/') }}">{{ AppHelper::decrypt($invoice->file, $invoice->tenantID) }}</a> &nbsp; <a class="ordinary_link status-bar" href="{{ URL::to('invoices/'.$invoice->tenant_invoice_id.'/remove_file/') }}"><i class="fa fa-times"></i>remove</a> 
				@endif	
				</p> 		 
		  	</td>   
		  </tr>		  
		
		</table>
	
		<div id="invoice_subj">
			<label>Subject or Purpose <span></span></label>
			<input type="text" class="mytext" value="<?php echo $invoice->subject; ?>" id="inv_subject" />
            <!-- Verify that recurring option is still available -->
            @if($request_type == 'invoice')
              <p><a href="" class="recurring_popup_open btn">{{ $invoice->recurring == 1 ? "Edit recurring" : "Make recurring" }}</a><p/>
            @endif
		</div><!-- END Invoice Subject -->
		
		<div id="terms">
		  <p class="last_updated">Last updated on <span><?php $dt = $invoice->updated_at->toDayDateTimeString(); ?>{{ $dt }}</span></p>
		  <!-- <h5><?php echo $preferences->footnote1 != "" ? $preferences->footnote1 : ""; ?></h5>
		  <textarea class="cw"><?php echo $preferences->footnote2 != "" ? $preferences->footnote2 : ""; ?></textarea> -->
		</div> 
		 
		 
	</div>
</div>
 
</div><!-- Inner Wrap-->

 
	<div id="markaspaid_popup" class="page_popup well">
		<h2>Mark as paid</h2> <br />
		
		{{ Form::open(array('url' => 'payments/'.$invoice->tenant_invoice_id.'/store', 'method' => 'POST')) }}
		
				<label>Date of payment<span></span></label>
	            <input type="text" name="date" class="txt issuedate" id="issuedate" autocomplete="off" />
	            
	 			<label>Payment method</label>
	            <select id='payment_method' name='payment_method' class="sel">
					<option value="" selected="selected">Select</option>
				    <option value="Bank transfer">Bank Transfer</option>
		            <option value="Cheque">Cheque</option>
		            <option value="Cash">Cash</option>
		            <option value="Online">Online</option>
			    </select>
			    
	      		<div class="cheque_section"> 
				 <label>Cheque number</label>
		            <input type="text" name="cheque_number" class="txt" id="cheque_number" value="{{ Input::old('cheque_number') }}" autocomplete="off" />
		        </div> 
		        
		        <div class="bank_transfer_section"> 
				 <label>Bank Transfer Reference</label>
		            <input type="text" name="bank_transfer_ref" class="txt" id="bank_transfer_ref" value="{{ Input::old('bank_transfer_ref') }}" autocomplete="off" />
		        </div> 
		        <br />
		        <input type="submit" id="record_payment" class="gen_btn" name="record_payment" value="Record payment" />
			    
		 {{ Form::close() }}
   		 <button class="markaspaid_popup_close btn btn-default">Cancel</button>  	 

   </div> <!-- END markaspaid_popup -->
   
    
   <div id="attachment_popup" class="page_popup well">
		<h2>Attach file to {{ $request_type }}</h2> 
		<p><strong>Acceptable formats: </strong> .doc .docx .xls .pdf .ppt .png .jpg .zip  
			<br /><strong>Max attachment size</strong>: 5MB</p>
		 {{ Form::open(array('url' => 'invoices/'.$invoice->tenant_invoice_id.'/attachment', 'method' => 'POST', 'files'=>true)) }}
		    <label>Select file</label> <input type="file" name="file" class="">
		    <br /> <br /> 
   		    <button class="attachment_popup_close btn cancelBtn">Cancel</button> <input type="submit" id="" class="gen_btn" name="" value="Click to Upload" />	      
		 {{ Form::close() }}
   </div> <!-- END attachment_popup -->


  <div id="recurring_popup" class="page_popup well">
    <h2>{{ $invoice->recurring == 1 ? "Edit" : "Set" }} Recurring option</h2>
    <p>Auto-generate invoice periodically.</p>
    {{ Form::open(array('url' => 'invoices/'.$invoice->tenant_invoice_id.'/recurring', 'method' => 'POST')) }}

    <label>Next Recurring date</label>
      <input type="text" value="<?php echo $invoice->recurring == 1 ? AppHelper::date_to_text(substr($invoice->recur_next_date, 0, 10), $preferences->date_format) : ""; ?>" name="next_recurring_date" class="txt next_recurring_date" id="next_recurring_date" autocomplete="off" />

    <label>Frequency</label>
      <select id='recur_schedule' name='recur_schedule' class="sel">
          <option <?php echo $invoice->recur_schedule  == "" || $invoice->recur_schedule == null ? "selected=selected": ""; ?> value="">Select</option>
          <option <?php echo $invoice->recur_schedule  == "Every week" ? "selected=selected": ""; ?> value="Every week">Every week</option>
          <option <?php echo $invoice->recur_schedule  == "Every two weeks" ? "selected=selected": ""; ?> value="Every two weeks">Every two weeks</option>
          <option <?php echo $invoice->recur_schedule  == "Every month" ? "selected=selected": ""; ?> value="Every month">Every month</option>
          <option <?php echo $invoice->recur_schedule  == "Every two months" ? "selected=selected": ""; ?> value="Every two months">Every two months</option>
          <option <?php echo $invoice->recur_schedule  == "Every three months" ? "selected=selected": ""; ?> value="Every three months">Every three months</option>
          <option <?php echo $invoice->recur_schedule  == "Every four months" ? "selected=selected": ""; ?> value="Every four months">Every four months</option>
          <option <?php echo $invoice->recur_schedule  == "Every six months" ? "selected=selected": ""; ?> value="Every six months">Every six months</option>
          <option <?php echo $invoice->recur_schedule  == "Every twelve months" ? "selected=selected": ""; ?> value="Every week">Every twelve months</option>
      </select>

    <label>Last Recurring Date</label>
    <input type="text" value="<?php echo $invoice->recurring == 1 ? AppHelper::date_to_text(substr($invoice->recurring_end_date, 0, 10),$preferences->date_format) : ""; ?>" name="last_recurring_date" class="txt last_recurring_date" id="last_recurring_date" autocomplete="off" />

    <label>Payment Terms (Due date)</label>
      <select id="recur_due_date_interval" class="recur_due_date_interval sel" name="recur_due_date_interval">
          <option <?php echo $invoice->recur_due_date_interval  == 0 ? "selected=selected": ""; ?> value="0">Same as Issue date</option>
          <option <?php echo $invoice->recur_due_date_interval  == 7 ? "selected=selected": ""; ?> value="7">7 days after Issue date</option>
          <option <?php echo $invoice->recur_due_date_interval  == 15 ? "selected=selected": ""; ?> value="15">15 days after Issue date</option>
          <option <?php echo $invoice->recur_due_date_interval  == 21 ? "selected=selected": ""; ?> value="21">21 days after Issue date</option>
          <option <?php echo $invoice->recur_due_date_interval  == 30 ? "selected=selected": ""; ?> value="30">30 days after Issue date</option>
      </select>
    <br />

    <label class="make_inline_block">Active</label>
      <input type="checkbox" value="1" <?php if($invoice->recur_status != 0){ echo "checked=\"checked\""; } ?> name="recur_status" id="recur_status" class="" /> &nbsp; &nbsp; &nbsp;

      <label class="make_inline_block">Auto send</label>
      <input type="checkbox" value="1" <?php if($invoice->auto_send != 0){ echo "checked=\"checked\""; } ?> name="auto_send" id="auto_send" class="" /> <br /><br />

    <button class="recurring_popup_close btn cancelBtn">Cancel</button> <input type="submit" id="update_recurring" class="gen_btn" name="" value="{{ $invoice->recurring == 1 ? "Save" : "Activate recurring" }} " />
    @if($invoice->recurring == 1)
      <a class="gen_btn" href="{{ URL::route('remove_invoice_recurring', $invoice->tenant_invoice_id) }}">Delete</a>
    @endif
    {{ Form::close() }}
  </div> <!-- END recurring_popup -->
   
   
  @stop
	
  @section('footer')
	
	<script src="{{ URL::asset('assets/js/jquery.datetimepicker.js') }}"></script>			 
	<script src="{{ URL::asset('assets/js/jquery.popupoverlay.js') }}"></script>
	  
	<script>
	  	
	  		$(document).ready(function() {	 
	  			
	  			
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

				$('.notetext, .mytext, #meta input').attr('disabled', 'disabled');	
				$('.makeDisabled').click(function(e) {
					alert('A file is already attached. Please delete the file if you want to attach a different file.')
				     return false;
				});
					
				// CONFIRM INVOICE DELETE
				if($('#deleteinvoice').length > 0){
					$('#deleteinvoice').click(function(){
						if(confirm('Delete this invoice?')){
							return true;
						}else{
							return false;
						}
					});
				}
				
				
				// CONFIRM QUOTE DELETE
				if($('#deletequote').length > 0){
					$('#deletequote').click(function(){
						if(confirm('Delete this quote?')){
							return true;
						}else{
							return false;
						}
					});
				}
				
				
				
				// Mark as paid overlay
				 
	   			$.fn.popup.defaults.pagecontainer = '.page-panel';
	   			
	   			// Initialize the plugin
	  			$('#markaspaid_popup').popup({
	  				opacity: 0.8,
	  				vertical: 'top',
	  				transition: 'all 0.3s',			    
				    outline: true, // optional
	    			focusdelay: 300, // optional
				});
				
				// Attachment
				$('#attachment_popup').popup({
	  				opacity: 0.8,
	  				vertical: 'top',
	  				transition: 'all 0.3s',			    
				    outline: true, // optional
	    			focusdelay: 300, // optional
				});


                // Recurring Option
                $('#recurring_popup').popup({
                    opacity: 0.8,
                    vertical: 'top',
                    transition: 'all 0.3s',
                    outline: true, // optional
                    focusdelay: 300, // optional
                });

                if($('#issuedate').length > 0)
                {
                    $('#issuedate').datetimepicker({
                        lang:'en',
                        timepicker:false,
                        format: $('.pref_dateformat').val(),
                        formatDate:'Y/m/d',
                        closeOnDateSelect:true
                    });
                }

                if($('#next_recurring_date').length > 0)
                {
                    $('#next_recurring_date').datetimepicker({
                        lang:'en',
                        timepicker:false,
                        format: $('.pref_dateformat').val(),
                        formatDate:'Y/m/d',
                        minDate: 0,
                        closeOnDateSelect:true
                    });
                }

                if($('#last_recurring_date').length > 0)
                {
                    $('#last_recurring_date').datetimepicker({
                        lang:'en',
                        timepicker:false,
                        format: $('.pref_dateformat').val(),
                        formatDate:'Y/m/d',
                        minDate: 0,
                        closeOnDateSelect:true
                    });
                }


                //////  Submit Recurring
                $('#update_recurring').on('click', function(){

                    if($.trim($('#next_recurring_date').val()) == ""){
                        alert('Enter the next recurring date');
                        return false;
                    }

                    if($.trim($('#recur_schedule').val()) == ""){
                        alert('Select the Frequency of billing');
                        return false;
                    }

                    if($.trim($('#last_recurring_date').val()) == ""){
                        alert('Enter the last recurring date');
                        return false;
                    }

                });


				
				
				$('.cheque_section').hide();
				$('.bank_transfer_section').hide();
				
				$('#payment_method').on('change', function() {
					
				   if($(this).val() == "Cheque"){
				   		
				   		$('.cheque_section').fadeIn();
				   		
				   }else{
				   	
				   		$('.cheque_section').fadeOut();
				   }
				   
				   
				   if($(this).val() == "Bank transfer"){
				   		
				   		$('.bank_transfer_section').fadeIn();
				   		
				   }else{
				   	
				   		$('.bank_transfer_section').fadeOut();
				   }
				   
				});


                /// Submit record payment
				$('#record_payment').on('click', function(){	
				 
					if($.trim($('#issuedate').val()) == ""){						
						alert('Enter the date of payment');						
						return false;
					}
				 
					
					if($.trim($('#payment_method').val()) == ""){						
						alert('Select the payment method');						
						return false;
					}
					
					
					  $tenant_invoice_id = $('.markaspaid_popup_open').attr('tInvId');
					  $issuedate = $('#issuedate').val();
					  $payment_method = $('#payment_method').val();
					  $cheque_number = $('#cheque_number').val();
					  $bank_transfer_ref = $('#bank_transfer_ref').val();
			  
				 	   var $data = "date="+$issuedate+"&payment_method="+$payment_method+"&cheque_number="+$cheque_number+"&bank_transfer_ref="+$bank_transfer_ref;
				 	   var jqxhr = $.ajax({ url: "../payments/" + $tenant_invoice_id + "/paid",
										 type: "POST",	
										 data: $data
						}).success(function($response) {
					 
						  //  alert("Invoice successfully maked as paid");						
								
						})
						.error(function() { alert("error"); })
						.complete(function() {
							 							
						    $(".markaspaid_popup_close").trigger("click");
						    window.location.reload(true);
			
					     });
			 	 
			 		return false;
			 	 
				});
	
	  		});
	 
	  </script>
	 
	@stop




