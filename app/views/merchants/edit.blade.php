@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::to('merchants') }}">Merchants</a> &raquo; Editing &raquo; {{ $merchant->company }}</h1>
	
	{{ Form::open(array('url' => 'merchant/update', 'method' => 'PUT')) }}
	
	<div id="edit_client_form">
		
		 @include('common.merchant_errors')
   
	    <div class="longbox">
		    	<label>Merchant name <span class="mand">*</span></label>
		            <input type="text" name="company" class="txt" id="company_" value="{{ $merchant->company }}" />
		        <label>Address Line 1 <span> </span></label>
		            <input type="text" name="add_1" class="txt" id="add_1" value="{{ $merchant->add_1 }}" />
		         
		        <label>City<span> </span></label>
		            <input type="text" name="city" class="txt" id="city" value="{{ $merchant->city }}" />
		        
		        <label>Postcode <span>(or Zip code)</span></label>
		            <input type="text" name="postal_code" class="txt" id="postal" value="{{ $merchant->postal_code }}" />
		        <label>Country</label>
		            <input type="text" name="country" class="txt" id="country" value="{{ $merchant->country }}" />
		         
		   </div><!-- END Long box -->	
		  
		   <div class="longbox">
		           
		        <label>Note</label>
		            <textarea id="notes" name="notes" class="txtarea">{{ $merchant->notes; }}</textarea>  
		   		 
		        <label>Email</label>
		            <input type="text" name="email" class="txt" id="email_" value="{{ $merchant->email }}" />
		        <label>Telephone<span></span></label>
		            <input type="text" name="phone" class="txt" id="phone" value="{{ $merchant->phone }}" />
		         
		         <input type="hidden" name="merchantId" value="{{ $merchant->id }}" /> <br />
			    <input type="submit" id="addnewmerchant" class="gen_btn" name="add_item" value="Save merchant" />  
			     @include('common.mandatory_field_message')
		   </div><!-- END Long box -->
		</div><!-- END Edit user_form -->
{{ Form::close() }}

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