@extends('layouts.download_receipt')

	@section('content')
	
	<?php use IntegrityInvoice\Utilities\AppHelper as AppHelper;  
		use Carbon\Carbon;
	?>
 
	<div class="receipt_cover"> 
		
	 	<div class="receiptpage">
	 		
	 	<div class="toparea">
	 		
	 	<div class="for_logo">
	   	 <?php      		    
		   $tenantID = $invoice->tenantID; $ext = '.png'; $logo_file = public_path(). '/te_da/'.$tenantID . '/'.$tenantID.$ext; ?>
				 
			@if (file_exists($logo_file))	
			
				@if($company->website != "" &&  $company->website != null)				 
				<a href=" " target="_blank"><img src="{{ public_path().'/te_da/'.$tenantID.'/'.$tenantID.'.png' }}" alt="" /></a>
				@else
				<img src="{{ public_path().'/te_da/'.$tenantID.'/'.$tenantID.'.png' }}" alt="" />
				@endif
			 
			@endif	
		 
		   </div><!-- END For Logo -->
	 
		    <div class="addr"><p><strong class="cname"><?php echo $company->company_name; ?></strong><br /><span><?php echo $company->add_1; ?><?php
					if($company->add_2 == ""){
						echo "";
					}else if($company->add_2 == $company->state){
						echo "";
					}else{ echo ", ".$company->add_2 . ", ";} ?> <?php echo $company->state; ?><?php echo "  <br />".$company->postal_code; ?> 
					@if($company->phone != "" && $company->phone != NULL)
					<?php echo ". Tel. ". $company->phone; ?> <br />
					@endif
					@if($company->website != "" && $company->website != NULL)
					<?php echo "Web. ". $company->website; ?></p></div>
			        @endif
			        </span>
			        </p>
				</div> <!-- END identity -->
				
		   </div> <!-- END toparea -->
				
			<h1>Receipt for Invoice {{ Apphelper::invoiceId($invoice->tenant_invoice_id) }}</h1>
			  
			<h3>Payment of <span class="amount"><?php echo Symfony\Component\Intl\Intl::getCurrencyBundle()->getCurrencySymbol($invoice->currency_code); ?>{{ $invoice->balance_due }}</span></h3>
			 
			<p class="received_from">Received with thanks from: {{ $client->company }} on {{ AppHelper::date_to_text(substr($invoice->updated_at, 0, 10), $preferences->date_format) }}.<p> 
 
			
	   </div><!-- END receiptpage  -->
	   
	 </div><!-- END receipt cover -->
	 
		<div id="foot">
		  <h4><?php echo $preferences->footnote1 != "" ? $preferences->footnote1 : ""; ?></h4>
		   <p><?php echo $preferences->footnote2 != "" ? $preferences->footnote2 : ""; ?></p>
		</div> 
		
	 
		<div id="signature">		  
		   <p>Signature: <br />
		   	 <?php echo $user->firstname . ' '.$user->lastname; ?>		    
		   	<br /> <strong class="cname"><?php echo $company->company_name; ?></strong> 
		   
		   </p>
		</div> 
	 
	 
	@stop