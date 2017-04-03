@extends('layouts.default')

	@section('content')	 
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a> &raquo; <a href="{{ URL::route('subscription') }}"> Subscription </a> &raquo; Payment history</h1>
 
 	<div id="history_page">
			 
	    <div class="longbox">
	       
			<table class="table">
       		<thead>
       			<tr>
       				<th class="sorting client_name_width"><i class=""></i>Plan</th>
       				<th class="sorting"><i class=""></i>Valid from</th>   
       				<th class="sorting"><i class=""></i>Valid to</th>          				 
 					<th class="sorting displayNone"><i class=""></i>Payment type</th>   
       				<th class="sorting"><i class=""></i>Amount paid</th>
       				<th class="sorting displayNone"><i class=""></i>Paid on</th>    
       			</tr>
       		</thead>
       		
	        <tbody>
	        	
        		<?php $row = 2; foreach($histories as $history): ?>
        		 
			    <?php if ($row % 2) {$colour = "light_g1";}else{$colour = "light_g2"; }; $row += 1; ?>
					<tr class="<?php echo $colour; ?>">
						<td><strong><?php echo IntegrityInvoice\Utilities\AppHelper::get_subscription_plan($history->subscription_type); ?></strong></td>
						<td><?php echo IntegrityInvoice\Utilities\AppHelper::date_to_text($history->valid_from, $date_format); ?></td>
						<td><?php echo IntegrityInvoice\Utilities\AppHelper::date_to_text($history->valid_to, $date_format); ?></td>
						<td class="displayNone"><?php echo $history->payment_system; ?></td>
						<td><?php echo 'GPB '.$history->amount; ?></td>
						<td class="displayNone"><?php echo IntegrityInvoice\Utilities\AppHelper::date_to_text($history->created_at, $date_format); ?></td>
					</tr>
			 
				<?php endforeach; ?>
				 
			</tbody>
       		
       	 </table>
    
 
	    </div><!-- END longbox -->
	    
	</div><!-- END history_page -->
 	
	@stop
	

@section('footer')

	<script>
	
		$(function(){
		 
		 	  if($('#appmenu').length > 0){
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_subscription').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop