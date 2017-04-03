@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('merchants', 'Merchants',  array(), array('class' => 'to_all')) }} &raquo; create</h1>
<?php if($limitReached == FALSE): ?>

{{ Form::open(array('url' => 'merchants/store', 'method' => 'POST')) }}
	<div id="new_client_form">

		 @include('common.merchant_errors')
		
	    <div class="longbox">

				<label>Merchant name <span class="mand">*</span></label>
		            <input type="text" name="company" class="txt" id="company_" value="{{ Input::old('company')}}" autocomplete="off" />
		        <label>Address line 1<span> </span></label>
		            <input type="text" name="add_1" class="txt" id="add_1" value="{{ Input::old('add_1')}}"  autocomplete="off" />		        
		        <label>City<span> </span></label>
		            <input type="text" name="city" class="txt" id="city" value="{{ Input::old('city')}}" />		        
		        <label>Postcode <span>(or zip code)</span></label>
		            <input type="text" name="postal_code" class="txt" id="postal_code" value="{{ Input::old('postal_code')}}" autocomplete="off" />		            
		       <label>Country</label>
		            <input type="text" name="country" class="txt" id="country" value="{{ Input::old('country')}}" /> 
		             
		   </div><!-- END Long box -->	
		  
		   <div class="longbox">
		   		
		        	   		 
		        <label>Email</label>
		            <input type="text" name="email" class="txt" id="email_" value="{{ Input::old('email')}}"  autocomplete="off" />
		        <label>Phone<span></span></label>
		            <input type="text" name="phone" class="txt" id="phone" value="{{ Input::old('phone')}}" autocomplete="off" />
		        <label>Note </label>
		            <textarea id="notes" name="notes" class="txtarea">{{ Input::old('note')}}</textarea>	
		
			     <input type="submit" id="addnewmerchant" class="gen_btn" name="add_item" value="Add merchant" />
			     @include('common.mandatory_field_message')

		   </div><!-- END Long box -->
		</div><!-- END new_user_form form -->
		
		
{{ Form::close() }}

<?php else: ?>
	<h3>You have reached your limit. Please consider upgrading if you wish to add more merchants.
		{{ HTML::linkRoute('subscription', 'UPGRADE NOW',  array(), array('class' => 'to_all')) }}
	</h3>
	
<?php endif; ?>
  
  @stop
  
	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Expenses');				 
				  $('.menu_all_merchants').addClass('selected');
			  }
			 
		});
		
	</script>
 
	@stop