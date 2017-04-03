@extends('layouts.download')

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
	 $l_id = $invoice->tenant_invoice_id;
	 
	 
	 
	 $enable_discount = true;
	 $enable_tax = true;
	  
	  if($invoice->enable_discount == 0) { $enable_discount = false; }else{ $enable_discount = true;}
      if($invoice->enable_tax == 0) { $enable_tax = false; }else{ $enable_tax = true;}
	  
	  // Default with discount and no tax
	  $colspan1 = $invoice->business_model == 0 ? 2 : 3;
	  $colspan2 = $invoice->business_model == 0 ? 4 : 5;
 
	  
	  // Only Discount enabled
	  if($enable_discount == true && $enable_tax == false)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 3;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 3 : 1;
		  
	  }
	  
	  // Only Tax enabled
	  if($enable_discount  == false && $enable_tax  == true)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 3;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 3 : 1;		   
	  }
	  
	   // Discount and tax enabled
	  if($enable_discount  == true && $enable_tax  == true)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 2;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 4 : 3;
		   
	  }
	  
	  // Discount and tax disabled
	  if($enable_discount  == false && $enable_tax  == false)
	  {
		  $colspan1 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 2 : 2;
		  $colspan2 = $invoice->business_model == 0 || ($invoice->business_model == 1 && $invoice->bill_option == 0 ) ? 1 : 0;
		   
	  }
	 
	?>
	
 <h1>{{ HTML::linkRoute('invoices', 'Invoices', array(), array('class' => 'to_all')) }} &raquo;  <span><?php echo AppHelper::invoiceId($l_id); ?></span></h1>
	 
 
<div id="invoicecontainer">
	
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
		 
        
        <div class="invoiceTopRight" id="invnum">
        	<table id="meta">
                <tr>
                    <td class="meta-head">Invoice ID</td>
                    <td><div class="cw inv_num"><?php echo AppHelper::invoiceId($l_id); ?></div></td>
                </tr>
                <tr>
                    <td class="meta-head">Issue Date</td>
                    <td><div><?php $inv_date = $invoice->created_at;
					 echo AppHelper::date_to_text($inv_date, $preferences->date_format);?></div>
					 </td>
                </tr>
                <tr>
                    <td class="meta-head">Amount Due</td>
                    <td><div><?php echo AppHelper::dumCurrencyCode($invoice->currency_id). " ". number_format($invoice->balance_due, 2, '.', ','); ?></div></td>
                </tr>
                <tr>
                    <td class="meta-head">Due Date</td>
                    <td><div><?php $inv_date = $invoice->due_date;
					 echo AppHelper::date_to_text($inv_date, $preferences->date_format);?></div></td>
                </tr>
            </table>
            
		</div> <!-- END invnum -->
 
 </div><!-- End Invoice Top -->

<?php // $this->load->view('invoices/currency'); ?>
 
		<table width="100%" id="">
		
		  <tr> 
		  	
		      @if($invoice->business_model == 0)
		      <th width="" class="alignLeft">Product</th>
		      @elseif($invoice->business_model == 1)
		      <th width="" class="alignLeft">Service</th>
		      @endif		      
		      <th width="">Rate</th>
		      @if($invoice->business_model == 0)
		      <th width="">Qty</th>
		      @elseif($invoice->business_model == 1 && $invoice->bill_option == 0)
		      <th width="">Hour(s)</th>
		      @endif
		      
		      @if($invoice->enable_discount)
		      <th width="">Discount</th>
		       
		      @endif
		      
		      @if($invoice->enable_tax)
		      <th width="">Tax</th>
		     
		      @endif	  
		      	      
		      <th width="">Line Total</th>
		  </tr>          
           
          <?php foreach($items as $item): ?>
		  <tr class="item-row">
		    
		      <td class="description"><textarea class="cw desc alignLeft"><?php echo preg_replace($find, $replace, $item[1]); ?></textarea></td>
		      <td><textarea class="cost cw"><?php echo number_format($item[2], 2, '.', ','); ?></textarea></td>
		      
		      @if($invoice->business_model == 0)
		      <td><textarea class="qty cw"><?php echo $item[3] ?></textarea></td>
		      @elseif($invoice->business_model == 1 && $invoice->bill_option == 0)
		      <td><textarea class="qty cw"><?php echo $item[3] ?></textarea></td>
		      @endif
		      
		      @if($invoice->enable_discount)
		      <td><?php echo AppHelper::get_discount_type($item[6], $item[7], $item[2], $item[3]); ?></td>
		      @endif
		      @if($invoice->enable_tax)
		      <td><?php echo AppHelper::get_tax_type(trim($item[4]),$preferences->tax_perc1, $preferences->tax_perc2); ?></td>  
		      @endif 
			  <td><span class="price"><?php echo $item[8]; ?></span></td>
		  </tr>
           <?php endforeach; ?>
 
		   <tr>
		      <td colspan="{{ $colspan1 }}" class="blank "><div class="fornote"><textarea style="height:120px; width:98%;" class="cwl notetext"><?php echo $invoice->note; ?></textarea></div></td>
		      <td colspan="{{ $colspan2 }}" class="total-line"><strong>Sub Total</strong></td>
		      <td class="total-value"><div><span class="c_subtotal" id="subtotal"><?php echo number_format($invoice->subtotal, 2, '.', ','); ?></span></div></td>
		  </tr>
		  
		  @if($invoice->enable_tax == 1)
          <tr id="mytax">
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="vat-line"><strong>( + ) Tax </strong></td>
		      <td class="vat-value"><div><span id="vat" class="c_vat"><?php echo number_format($invoice->tax_val, 2, '.', ','); ?></span></div></td>
		  </tr>		  
		  @endif
		  
		  @if($invoice->enable_discount == 1)
           <tr id="mydiscount">
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="vat-line"><strong>( - ) Discount </strong></td>
		      <td class="vat-value"><div><span id="discount" class="c_discount"><?php echo  number_format($invoice->discount_val, 2, '.', ','); ?></span></div></td>
		  </tr>
		  @endif
		   
		  <tr>
		      <td colspan="{{ $colspan1 }}" class="blank"> </td>
		      <td colspan="{{ $colspan2 }}" class="total-line balance">NET Total Due (<span class="cur_symbol"><?php echo AppHelper::dumCurrencyCode($invoice->currency_id); ?></span>)</td>
		      <td class="total-value balance"><div><span class="due"><?php echo number_format($invoice->balance_due, 2, '.', ','); ?></span></div></td>
		  </tr>
		  
		
		</table>
	
		<div id="invoice_subj">
			<label>Subject or Purpose <span></span></label>
			<input type="text" class="mytext" value="<?php echo $invoice->subject; ?>" id="inv_subject" />
		</div><!-- END Invoice Subject -->
		
		<div id="terms">
		  <h5><?php echo $preferences->footnote1 != "" ? $preferences->footnote1 : ""; ?></h5>
		  <textarea class="cw"><?php echo $preferences->footnote2 != "" ? $preferences->footnote2 : ""; ?></textarea>
		</div> 
		 
</div>
 

	@stop