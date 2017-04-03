@extends('layouts.public_invoice')

	@section('content')
	
	<?php use IntegrityInvoice\Utilities\AppHelper as AppHelper; ?>
 
 	 
<div id="" class="makeCenter">
 
 	   <div class="">
  	
		 <div class="">
		 	
		 	<?php      
		     function addhttp($url) {
			    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
			        $url = "http://" . $url;
				    }
				    return $url;
			 }
	       
		    $tenantID =  $data['tenantID']; $ext = '.png'; $logo_file =  public_path(). '/te_da/'.$tenantID . '/'.$tenantID.$ext; ?>
				 
			@if (file_exists($logo_file))			 
				<img class="gen_logo_size" src="{{ Config::get('app.app_main_domain').'/te_da/'.$tenantID.'/'.$tenantID.'.png' }}" alt="" />			 
			@endif	
	 	</div><!-- END -->
		 
		<div id="" class="">	
		   <div class="cw" id="address"><p><strong class="cname"><?php echo $data['company_name']; ?></strong><br />Email: {{ $data['receiver_email'] }}</div>
	      
		</div> <!-- END identity -->
	
	</div><!-- END headwrap -->
	
	<div class="success_message">
		<div class="credit_card"><img src=" {{ URL::asset('assets/img/icon_store_creditcards.png') }}" alt="" /></div>
		<h2>Payment was successfully processed for Invoice <?php echo $data['tenant_invoice_id']; ?>.</h2>
		
		<p>Thank you for your business.</p>
		
	</div><!-- END Success message -->
 
 
</div> <!-- END invoicecontainer -->
 
 
 
@stop


@section('footer')
  
	<script>
	
   		 $(document).ready(function() {
 
   		 	 
		});
 
    </script>
 
   @stop