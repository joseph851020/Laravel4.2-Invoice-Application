@extends('layouts.default')

	@section('content')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::route('currency_rates') }}">Currency rate</a> &raquo; Edit</h1>
 
 
{{ Form::open(array('url' => 'currency-rates/update', 'method' => 'PUT')) }}

	@include('common.currency_rate_errors')

	<input type="hidden" name="date_format" class="date_format" value="{{ $preferences->date_format }}" />
	<div id="add_expense_form">
		 	
	    <div class="longbox">
	    	   
	    	   <p><strong>Home currency: {{ $preferences->currency_code }} - {{ $home_currency }}</strong></p>
	    	   <input type="hidden" id="from_currency" value="{{ $preferences->currency_code }}" />
	    	   <p>
	    	   <strong>To: </strong>Currency - ({{ $currencyrate->currency_code }})<br /><br /></p>
		        
	            <label for="unit_exchange_rate">Exchange rate</label>
	            <label class="" for="unit_exchange_rate"><span class="cover_conversion_text">1 {{ $preferences->currency_code }} equals what <span id="sel_currency"></span> ?</span></label> 	          
	            
	            <input type="text" name="unit_exchange_rate" class="txt" id="unit_exchange_rate" value="{{ $currencyrate->unit_exchange_rate }}" />	  
	            
	            <input type="hidden" value="{{ $currencyrate->currency_code }}" name="currency_code" id="currency_code">           
	            <input type="submit" id="editCurrencyRate" class="gen_btn" name="editCurrencyRate" value="Save currency rate" />
		       @include('common.mandatory_field_message')
	  	    
	   </div><!-- END longbox -->
   
</div><!-- END add_expense_form -->
 

{{ Form::close() }}
 
  @stop
  

  @section('footer')
 
		 <script>
  	
  		$(document).ready(function() {	
  			 
  			$('.cover_conversion_text').hide();
  			
  			if($('#appmenu').length > 0){
			    $('.settings_all_menu').addClass('selected_group'); 		 
		  		$('.menu_currency_rate_settings').addClass('selected');		  		
		  		$('.settings_all_menu ul').css({'display': 'block'});
		    }
		    
		     
	   		$('input[type=submit]').click(function(){	
			  
				if($.trim($('#unit_exchange_rate').val()) == ""){						
					alert('Enter the exchange rate to the home currency');						
					return false;
				}
				
				if($.trim($('#currency_code').val()) == ""){						
					alert('Currency code missing');						
					return false;
				}
			 
			});

  		});
  		
  </script>
 
@stop