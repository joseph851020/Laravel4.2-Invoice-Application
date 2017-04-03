@extends('layouts.default')

	@section('content')	 
	<?php use IntegrityInvoice\Utilities\AppHelper as Apphelper; ?>
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::to('subscription') }}">Subscription</a> &raquo; Secure Payment</h1>
 	<div class="integrityinvoice_right"><img src=" {{ URL::asset('integrityinvoice_logo.png') }}" alt="" /></div>
 	<div id="card_page">
			 {{ Form::open(array('id' => 'billingform', 'url' => 'subscription/process_card', 'method' => 'POST')) }}
		 
	    <div class="paywithcard">
	    	
	    	<div class="credit_card"><img src=" {{ URL::asset('assets/img/creditcards3.png') }}" alt="" /></div>
	    	 <div class="payment-errors"></div>
	    	
	    	<div class="paymentform_wrap">
	    		<label>Card Number: <span>The 16 digits on the front of your card.</span></label>
	    		<input class="txt" type="text" data-stripe="number">
	    	</div><!-- END paymentform_wrap -->
	    	
	    	<div class="paymentform_wrap">
	    		<label>CVC: <span>The last 3 digits displayed on the back of your card.</span></label>
	    		<input class="txt" type="text" data-stripe="cvc">
	    	</div><!-- END paymentform_wrap -->
	    	
	    	<div class="paymentform_wrap">
	    	 <label>Expiry date: <span></span></label>
	    	 {{ Form::selectMonth(null, null, ['data-stripe' => 'exp-month', 'class' => 'sel']) }}
	    	 {{ Form::selectYear(null, date('Y'), date('Y') + 10, null, ['data-stripe' => 'exp-year', 'class' => 'sel']) }}
	    	</div><!-- END paymentform_wrap -->
	    	 
	    	 <input type="hidden" id="publishable_key" value="{{ $publishable_key }}">
	    	 
	    	 <input type="hidden" id="email" name="email" value="{{ $companyEmail }}">
	    	 <input type="hidden" id="token_mount" name="token_mount" value="{{ $token_mount }}">
	    	 
	    	 <input type="hidden" id="item_name" name="item_name" value="{{ $item_name }}">
	    	 <input type="hidden" id="item_number" name="item_number" value="{{ $item_number }}">
	    	 <input type="hidden" id="apply_upgrade_amount" name="apply_upgrade_amount" value="{{ $apply_upgrade_amount }}">
	    	 <input type="hidden" id="applied_upgrade_amount" name="applied_upgrade_amount" value="{{ $applied_upgrade_amount }}">
	    	 <input type="hidden" id="thisplan" name="thisplan" value="{{ $thisplan }}">
	    	 <input type="hidden" id="thisplan_price" name="thisplan_price" value="{{ $thisplan_price }}">
	    	 <input type="hidden" id="subcr_duration" name="subcr_duration" value="{{ $subcr_duration }}">
	    	 <input type="hidden" id="tenantID" name="tenantID" value="{{ $tenantID }}">
	    	 <input type="hidden" id="last_recorded_start_date" name="last_recorded_start_date" value="{{ $last_recorded_start_date }}">
	    	 <input type="hidden" id="last_recorded_end_date" name="last_recorded_end_date" value="{{ $last_recorded_end_date }}">
	    	 <input type="hidden" id="upgrading_from_unexpired_account" name="upgrading_from_unexpired_account" value="{{ $upgrading_from_unexpired_account }}">
	    	 <input type="hidden" id="extending_account" name="extending_account" value="{{ $extending_account }}">
	    	 <input type="hidden" id="renewing_expired_account" name="renewing_expired_account" value="{{ $renewing_expired_account }}">
	    	 <input type="hidden" id="subcr_plan" name="subcr_plan" value="{{ $subcr_plan }}">
	    	 
	    	 <input type="submit" value="Pay Now  &pound;{{ $amount_topay }}" class="btn">
	    	 
	    	 <div class="secure_message"><img src=" {{ URL::asset('assets/img/safe_secure.png') }}" alt="" /></div>
	   
	    </div><!-- END longbox -->
	     {{ Form::close() }}
	     
	     <!-- <a class="btn" href="{{ URL::to('subscription') }}">Back</a> -->
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
			  
			  
			   $('.paymentform_wrap select').select2({ width: 'element' });
		 
		});
		
	</script>

	 <script src="https://js.stripe.com/v2/"></script>
	 <script src="{{ URL::asset('assets/js/billing.js') }}"></script>
	
@stop