@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Account Settings</h1>
	
	{{ Form::open(array('url' => 'setting/update', 'method' => 'put')) }}
   
	@if($errors->has())
	<div class="flash error">
		<ul>	 
			{{ $errors->first('page_record_number', '<li>:message</li>') }}
			{{ $errors->first('date_format', '<li>:message</li>') }}	 
		</ul>
	</div>
	@endif 
 
   	<div id="preference_form" class="tab-container">
   		
   		 <ul class='etabs'>
		   <li class='tab general'><a href="#about_business">General</a></li>
		   <li class='tab notes'><a href="#note_payment">Notes &amp; Payment Info</a></li>
		   <li class='tab reminder'><a href="#invoice_reminder">Invoice Emails </a></li>
		   <li class='tab progress'><a href="#progress_receipt">Receipt Emails</a></li>
		 </ul>
   		
   <div class='panel-container'>
   	 &nbsp; &nbsp; <input type="submit" id="update_prefs" class="update_prefs_btn gen_btn" name="update_prefs" value="Update" />
   	<div id="about_business">
   			
		    <div class="longbox">
		    	
		    	<label>What do you provide?  <span class="mand">*</span></label>
	             <select id="business_model" name="business_model" class="sel"> 
	            	<option  <?php echo $preferences->business_model  == 1 ? "selected=selected": ""; ?> value="1">Services</option>            
	                <option <?php echo $preferences->business_model  == 0 ? "selected=selected": ""; ?> value="0">Products </option>		                                
	             </select>
		            
	           <label>How do you bill if you provide services?<span> </span></label>
	            <select id="bill_option" name="bill_option" class="sel">            
	                <option <?php echo $preferences->bill_option  == 0 ? "selected=selected": ""; ?> value="0">Per Hour </option>
	                <option  <?php echo $preferences->bill_option  == 1 ? "selected=selected": ""; ?> value="1">Per Project</option>                 
	            </select>
		            
		         <label>Reg. Number<span> (Business registration) </span></label>
		            <input type="text" name="company_reg" class="txt" id="" value="<?php echo htmlentities($preferences->company_reg); ?>" placeholder="Optional" />
		            
		        <label>Home Currency <span class="mand">*</span></label>
	            <select name="currency" id="the_currency" class="sel">
				    <option value="<?php ?>" selected="selected">- select currency -</option>
				    <?php foreach($currencies as $currency): ?>
				        <option <?php echo isset($preferences->currency_code) && 
				        $preferences->currency_code == $currency->three_code ? "selected=\"selected\"" : ""; ?> value="<?php echo $currency->three_code; ?>"><?php echo $currency->country_currency; ?></option>
				    <?php endforeach; ?>
				</select>
				
	           <label>Date Format <span class="mand">*</span></label>
	            <select id="date_format" name="date_format" class="sel">            
	                <option <?php echo $preferences->date_format  == "dd/mm/yyyy" ? "selected=selected": ""; ?> value="dd/mm/yyyy">Day / Month / Year</option>
	                <option  <?php echo $preferences->date_format  == "mm/dd/yyyy" ? "selected=selected": ""; ?> value="mm/dd/yyyy">Month / Day / Year</option>	                
	            </select>
		            
		        <label>Industry</label>
						<?php echo IntegrityInvoice\Utilities\AppHelper::getIndustryList($preferences->industry); ?>
						    
	           <label>Show discount column on invoice</label>
	            <select id="enable_discount" name="enable_discount" class="sel">            
	                <option <?php echo $preferences->enable_discount  == 1 ? "selected=selected": ""; ?> value="1">Yes</option>
	                <option  <?php echo $preferences->enable_discount  == 0 ? "selected=selected": ""; ?> value="0">No </option>                 
	            </select>
		            
		       <label>Show tax column on invoice</label>
	            <select id="enable_tax" name="enable_tax" class="sel">                
	                <option  <?php echo $preferences->enable_tax  == 1 ? "selected=selected": ""; ?> value="1">Yes</option>
	                <option <?php echo $preferences->enable_tax  == 0 ? "selected=selected": ""; ?> value="0">No </option>             
	            </select>
		        
		        <label>Records per page</label>
	            <select name="page_record_number" id="" class="sel">            
	                <option <?php echo $preferences->page_record_number  == "5" ? "selected=selected": ""; ?> value="5">5</option>
	                <option <?php echo $preferences->page_record_number  == "10" ? "selected=selected": ""; ?> value="10">10</option>
	                <option <?php echo $preferences->page_record_number  == "15" ? "selected=selected": ""; ?> value="15">15</option>
	                <option <?php echo $preferences->page_record_number  == "20" ? "selected=selected": ""; ?> value="20">20</option>
	                <option <?php echo $preferences->page_record_number  == "50" ? "selected=selected": ""; ?> value="50">50</option>
	                <option <?php echo $preferences->page_record_number  == "100" ? "selected=selected": ""; ?> value="100">100</option>
	            </select>
	           
		    </div> <!-- END longbox -->   
		    
		    <div class="longbox">
		    	
		    	<label>Time zone <span class="mand">*</span></label>
					   <?php echo IntegrityInvoice\Utilities\AppHelper::getTimeZonesList($preferences->time_zone); ?> 
		    	
		    	 <label>Invoice Prefix<span> (Optional, Max chars. is 3) </span></label>
		            <input type="text" name="invoice_prefix" class="txt" id="" value="<?php echo htmlentities($preferences->invoice_prefix); ?>" placeholder="Optional" />
		    	 
		        <label>VAT ID<span> (If you're VAT registered) </span></label>
		            <input type="text" name="vat_id" class="txt" id="" value="<?php echo htmlentities($preferences->vat_id); ?>" placeholder="Optional" />
		    
		        <label>Tax 1 label / name<span> (e.g. VAT) </span></label>
		            <input type="text" name="tax_1name" class="txt" id="" value="<?php echo htmlentities($preferences->tax_1name); ?>" />
		                
		        <label>Tax 1 value in percentage<span> (e.g. 17.50) </span></label>
		            <input type="text" name="tax_perc1" class="txt" id="" value="<?php echo htmlentities($preferences->tax_perc1); ?>" />
		        
			     <label>Tax 2 label / name<span> (e.g. Duty Tax) </span></label>
	           		 <input type="text" name="tax_2name" class="txt" id="" value="<?php echo htmlentities($preferences->tax_2name); ?>" />
	            <label>Tax 2 value in percentage<span> (e.g. 20.00) </span></label>
			         <input type="text" name="tax_perc2" class="txt" id="" value="<?php echo htmlentities($preferences->tax_perc2); ?>" />           
	            
		        <label>Footnote 1<span> (up to 10 words / 60 chars) </span></label>
		            <input type="text" name="footnote1" class="txt" id="" value="<?php echo htmlentities($preferences->footnote1); ?>" placeholder="Optional" />
		
		        <label>Footnote 2<span> (up to 10 words / 60 chars) </span></label>
		            <input type="text" name="footnote2" class="txt" id="" value="<?php echo htmlentities($preferences->footnote2); ?>" placeholder="Optional" />
		            
		 
		   	</div> <!-- END longbox -->
	   
	   </div><!-- END about business -->
    
	
	<div id="note_payment">
		
		<h3><i class="fa fa-info-circle"></i> These are the default notes used on your invoices and quotes, you can change them.</h3><br />
		
	    <div class="prefm1">
	        <label>Invoice note <span class="mand">*</span></label>
	            <textarea id="" name="invoice_note" class="txtarea setting_full_width_textarea"><?php echo htmlentities($preferences->invoice_note); ?></textarea>
	    </div>
	    
	    <div class="prefm1">
	        <label>Quote note <span class="mand">*</span></label>
	            <textarea id="" name="quote_note" class="txtarea setting_full_width_textarea"><?php echo htmlentities($preferences->quote_note); ?></textarea>
	    </div>
	    <div class="prefm1">
	        <label>Payment information<span> (E.g. bank account details) </span><span class="mand">*</span></label>
	            <textarea id="" name="payment_details" class="txtarea setting_full_width_textarea"><?php echo htmlentities($preferences->payment_details); ?></textarea>
	    </div>
    
    </div><!-- END note_payment -->
    
    <div id="invoice_reminder">
    	<h3><a class="legend_popup_open" href="#invoice_reminder"><i class="fa fa-info-circle"></i> Please click here to view the variable (special items in capital letters) meanings.</a></h3>
	   
	    <div class="prefm1">
	    	<div class="pref_message">
	    	   <h4>Invoice Message</h4>
	    	   <p>This message is the template that Sighted uses by default when you send invoice to your clients.</p>
	    	</div><!-- END pref_message -->
	    	
	   		 <label>Email subject <span class="mand">*</span></label>
	            <input type="text" class="txt setting_full_width_text" id="" name="invoice_send_message_subject" value="<?php echo htmlentities($preferences->invoice_send_message_subject); ?>" />
	     
	        <label>Email body <span class="mand">*</span></label>
	            <textarea id="" name="invoice_send_message" class="txtarea setting_full_width_textarea2"><?php echo htmlentities($preferences->invoice_send_message); ?></textarea>
	    </div>
	    <div class="prefm1">
	    	
	    	<div class="pref_message">
	    	   <h4>Quote Message</h4>
	    	   <p>This message is the template that Sighted uses by default when you send quote to your clients.</p>
	    	</div><!-- END pref_message -->
	    	
	   		 <label>Email subject <span class="mand">*</span></label>
	   		  <input type="text" class="txt setting_full_width_text" id="" name="quote_send_message_subject" value="<?php echo htmlentities($preferences->quote_send_message_subject); ?>" />
	        <label>Email body <span class="mand">*</span></label>
	            <textarea id="" name="quote_send_message" class="txtarea setting_full_width_textarea2"><?php echo htmlentities($preferences->quote_send_message); ?></textarea>
	    </div>
	    
	    <div class="prefm1">
	    	 <div class="pref_message">
	    	   <h4>Reminder Message</h4>
	    	   <p>This message is the template that Sighted uses by default when you send invoice reminder to your clients.</p>
	    	</div><!-- END pref_message -->
	    	
	    	<label>Email subject <span class="mand">*</span></label>
	            <input type="text" name="reminder_message_subject" class="txt setting_full_width_text" id="" value="<?php echo htmlentities($preferences->reminder_message_subject); ?>" />
	        <label>Email body <span class="mand">*</span></label>
	            <textarea id="" name="reminder_message" class="txtarea setting_full_width_textarea2"><?php echo htmlentities($preferences->reminder_message); ?></textarea>
	    </div>
    
     </div><!-- END invoice_reminder -->
     
     <div id="progress_receipt">
     	
     	<h3><a class="legend_popup_open" href="#progress_receipt"><i class="fa fa-info-circle"></i> Please click here to view the variable (special items in capital letters) meanings.</a></h3>
     
	    <div class="prefm1">
	    	
	     <div class="pref_message">
	    	   <h4>Payment Acknowledgement Message</h4>
	    	   <p>This message is the template that Sighted uses by default when you send receipt (payment acknowledgement) to your clients.</p>
	     </div><!-- END pref_message -->
	    	
	    	<label>Email subject <span class="mand">*</span></label>
	            <input type="text" name="progress_payment_message_subject" class="txt setting_full_width_text" id="" value="<?php echo htmlentities($preferences->progress_payment_message_subject); ?>" />
	        <label>Email body <span class="mand">*</span></label>
	            <textarea id="" name="progress_payment_message" class="txtarea setting_full_width_textarea2"><?php echo htmlentities($preferences->progress_payment_message); ?></textarea>
	    </div>
	    <div class="prefm1">
	    	
	    	<div class="pref_message">
	    	   <h4>Receipt / Thank You Message</h4>
	    	   <p>This message is the template that Sighted uses by default when you send full payment pdf receipt to your clients.</p>
	    	</div><!-- END pref_message -->
	    	
	    	<label>Email subject <span class="mand">*</span></label>
	            <input type="text" class="txt setting_full_width_text" id="" name="thank_you_message_subject" value="<?php echo htmlentities($preferences->thank_you_message_subject); ?>" />
	        <label>Email body <span class="mand">*</span></label>
	            <textarea id="" class="txtarea setting_full_width_textarea2" name="thank_you_message"><?php echo htmlentities($preferences->thank_you_message); ?></textarea>
	       
	    </div> 
    </div><!-- END progress_receipt -->
    
    
    <div class="btn-submit">
       <input type="submit" id="update_prefs" class="update_prefs_btn gen_btn" name="update_prefs" value="Update" />
       @include('common.mandatory_field_message')
    </div><!-- END btn-submit -->
    
    </div><!-- END panel-container -->	 
    	

</div><!-- END Preference form -->
<div id="legend_popup" class="page_popup well">
<div id="legend-variables">
	<table>
		<th>Legend / Variable</th>
		<th>Meaning</th>
		
		<tr>
			<td>_INVOICE_NUMBER_</td>
			<td>Will output the invoice ID / number of the invoice.</td>
		</tr>
		<tr>
			<td>_QUOTE_NUMBER_</td>
			<td>Will output the quote ID / number of the quote.</td>
		</tr>
		<tr>
			<td>_CLIENT_COMPANY_ </td>
			<td>Will displays the name of your client company or business.</td>
		</tr>
		
		<tr>
			<td>_CLIENT_CONTACT_PERSON_ </td>
			<td>Will displays the name of the contact person of your the client.</td>
		</tr>
		
		<tr>
			<td>_AMOUNT_DUE_ </td>
			<td>will show the total amount which is due on an invoice.</td>
		</tr>
		<tr>
			<td>_STILL_TO_PAY_</td>
			<td>Will show how much left to be paid on an invoice.</td>
		</tr>
		
		<tr>
			<td>_DUE_DATE_</td>
			<td>Will show the due date set on an invoice.</td>
		</tr>
		
		<tr>
			<td>_SENDER_COMPANY_</td>
			<td>Will display your business or company name.</td>
		</tr>
		
	</table>
 
</div><!-- END Legend Variables -->

</div><!-- END legend_popup  -->
 
{{ Form::close() }}

@stop


@section('footer')

 	<script src="{{ URL::asset('assets/js/jquery.hashchange.min.js') }}"></script>
	<script src="{{ URL::asset('assets/js/jquery.easytabs.min.js') }}"></script>
	<script src="{{ URL::asset('assets/js/jquery.popupoverlay.js') }}"></script>
 
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){
				 
				  $('.settings_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_general_settings').addClass('selected');		  		
		  		  $('.settings_all_menu ul').css({'display': 'block'});
			 }

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                $('#preference_form select').select2({ width: 'element' });
            }

			 $.fn.popup.defaults.pagecontainer = '.page-panel';
			 
			 // Initialize the plugin
  			$('#legend_popup').popup({
  				opacity: 0.8,
  				vertical: 'top',
  				transition: 'all 0.3s',			    
			    outline: true, // optional
    			focusdelay: 300, // optional
    			onopen: function(e) {
				   
				}
			});
			 
		});
		
	</script>
  
@stop