@extends('layouts.default')

	@section('content')	 
 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Payment Gatweay Settings</h1>
	
		
	@if($errors->has())
	<div class="flash error">
		<ul>
			{{ $errors->first('paypal_email', '<li>:message</li>') }}
			{{ $errors->first('stripe_secret_key', '<li>:message</li>') }}
			{{ $errors->first('stripe_publishable_key', '<li>:message</li>') }}
		</ul>
	</div>
	@endif  
  
	
	{{ Form::open(array('url' => 'paymentgateways', 'method' => 'put')) }}
	
   <p>&nbsp;</p> 
   <h3>Stripe For Credit / Debit Card</h3>
   
   <p> If you don't have a stripe account please register <a href="https://www.stripe.com" target="_blank">here</a> for free.</p>
		
 	<div id="preference_profile">
        <div class="shortbox">
        	<img class="" src="{{ URL::asset('assets/img/icon_store_creditcards.png') }}" alt="" /> 
            <label>Stripe Secret key</label>
            <input type="text" name="sct_key" class="txt" value="{{ isset($secret_key) && $secret_key != "" ? $secret_key : Input::old('sct_key') }}" />            
            <label>Stripe Publishable key</label>
            <input type="text" name="pub_key" class="txt" value="{{ isset($publishable_key) && $publishable_key != ""  ? $publishable_key : Input::old('pub_key') }}" /><br />            
            <input type="submit" class="btn" value="Save Stripe settings" />
     	</div>
 
	 
	<h3>Paypal Payment Settings</h3>
	  
        <div class="shortbox"><br />
        	<img class="" src="{{ URL::asset('assets/img/icon_store_paypal.png') }}" alt="" />
            <label>PayPal email</label>
            <input type="text" name="paypal_email" class="txt" value="{{ isset($paypal_email) && $paypal_email != "" ? $paypal_email : Input::old('paypal_email') }}" /><br />            
            <input type="submit" class="btn" value="Save Paypal settings" />
     	</div>
       
     </div>
    
	{{ Form::hidden('tenantID', Session::get('tenantID')) }}
	{{ Form::close() }}
		
	@stop
	

	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){				 
				 
				  $('.settings_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_payment_gateway').addClass('selected');		  		
		  		  $('.settings_all_menu ul').css({'display': 'block'});
			 }
			 
		});
		
	</script>
 
	@stop