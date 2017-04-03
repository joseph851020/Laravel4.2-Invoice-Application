@extends('layouts.default')

	@section('content')	 
	
	<h1>Payment processed using PayPal</h1>
	<div class="subscription_show" class="group">
	<?php if (Input::all()): ?>
	<?php $data = Input::all(); ?>
	<p class="messageboxok">Your current account is: <span><?php echo $subscription; ?></span></p>
	<p><strong>Amount:</strong> <?php echo $data['mc_gross']; ?><br />
	<strong>Payment status:</strong> <?php echo $data['payment_status']; ?><br />
	<strong>Plan and duration:</strong> <?php echo $data['item_name']; ?><br />
	</p>
	<p>See full payment history, <a href=" {{ URL::to('subscriptions/history') }}">click kere</a></p>
	<?php endif; ?>
	
	<?php /*foreach($data as $key => $value){
		echo " $key => $value<br />";
	} */?>
	</div><!-- END subscription_show -->
	
	@end
	

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