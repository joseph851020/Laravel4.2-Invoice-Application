@extends('layouts.default')

	@section('content')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::route('currency_rates') }}">Currency rate</a> &raquo; New</h1>
 
 
{{ Form::open(array('url' => 'currency-rates/store', 'method' => 'POST')) }}

	@include('common.currency_rate_errors')

	<input type="hidden" name="date_format" class="date_format" value="{{ $preferences->date_format }}" />
	<div id="add_expense_form">
		 	
	    <div class="longbox">
	    	   <label>Your Home currency is: </label>
	    	   <p><strong>{{ $preferences->currency_code }} - {{ $home_currency }}</strong></p>
	    	   <input type="hidden" id="from_currency" value="{{ $preferences->currency_code }}" />
	    	   <p>
	    	   <strong>To: </strong>
	    	   </p>
	            <label>Which Currency? <span class="mand">*</span></label>
		        <select name="currency" id="currencylist" class="sel" <?php echo $preferences->currency_code != null || $preferences->currency_code != "" ? "": "";  ?>>			  		 
				    <option value="" selected="selected">Select</option>			 
				    <?php echo IntegrityInvoice\Utilities\AppHelper::getUserCurrencyListOptionsExpExist($currency_list, 'USS'); ?>				
				      
				</select>
	       
	            <label for="unit_exchange_rate">Currency Exchange rate <span class="mand">*</span></label>
	            <label class="" for="unit_exchange_rate"><span class="cover_conversion_text">1 {{ $preferences->currency_code }} equals what <span id="sel_currency"></span> ?</span></label> 	          
	            
	            <input type="text" name="unit_exchange_rate" class="txt" id="unit_exchange_rate" value="{{ Input::old('unit_exchange_rate')}}" />	             
	            <input type="submit" id="addCurrencyRate" class="gen_btn" name="addCurrencyRate" value="Add currency rate" />
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

            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                $('#currencylist').select2({ width: 'element' });
            }
		    
		    
	    	$('#currencylist').on('change', function(){
				// $('.cover_conversion_text').fadeIn();
				
				$from_currency = $(this).val();
				$to_currency = $('#from_currency').val();				
			  
				  var $api_data = "from_currency="+$from_currency+"&to_currency="+$to_currency;
				  
 	                $.ajax({ url: "../currency-rates/api", 
						    type: "POST",	
						    data: $api_data
						})
						.success(function($response) {				 	 
					 		$('#unit_exchange_rate').val($response);	
						})
						.error(function() {
						        // alert("Error getting currency rates.");
						 })
						.complete(function(){});
				 
				
			    //$('#sel_currency').html($(this).val());			 
			});
			
		 
  			 
	   		$('input[type=submit]').click(function(){	
			  
				if($.trim($('#unit_exchange_rate').val()) == ""){						
					alert('Enter the exchange rate to the home currency');						
					return false;
				}
			 
			});

  		});
  		
  </script>
 
@stop