@extends('layouts.default')

	@section('content')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('expenses', 'Expenses', array(), array('class' => 'to_all')) }} &raquo; New</h1>
	
<?php  if($limitReached == FALSE): ?>
	
 
{{ Form::open(array('url' => 'expenses/store', 'method' => 'POST', 'files'=>true)) }}

	@include('common.expense_errors')

	<input type="hidden" name="date_format" class="date_format" value="{{ $preferences->date_format }}" />
	<div id="add_expense_form">
		 	
	    <div class="two_sides">
	    	 <div class="left_side">
	    	 	
	    	 	<label>Date <span class="mand">*</span> <span>(select)</span></label>
		        <input type="text" name="created_at" class="txt issuedate" id="issuedate" value="{{ Input::old('created_at')}}" autocomplete="off" />
		         
	            <label>Category</label>
	            <select id="category" name="category_id" class="sel">
	                <option value="1" selected="selected">- select -</option>
                 @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->expense_name }}</option>
                 @endforeach 
	            </select>
	            
	            <label>Details  / Description <span class="mand">*</span> </label>
	    	    <textarea id="note" class="txtarea" name="note">{{ Input::old('note')}}</textarea>
	          
	            <label>Net Amount <span class="mand">*</span> <span>(e.g 75.99)</span></label>
	            <input type="text" name="amount" class="txt" id="amount" value="{{ Input::old('amount')}}" />
	            
			 </div><!-- END left_side -->
				
			 <div class="right_side">
			 	
			 	<label>Merchant / Seller <span class="mand">*</span></label>
		         <select id="merchant" name="merchant_id" class="sel">
	                <option value="" selected="selected">- select -</option>	                
	                <option value="newmerchant"> New merchant </option>	                
	             	@foreach($merchants as $merchant)
	                <option value="{{ $merchant->id }}">{{ $merchant->company }}</option>
	             	@endforeach
	            </select>	 
	            
	            <br /> 
	    	   <div class="newMerchant"> 
				 <label>New merchant's name</label>
		            <input type="text" name="newmerchant" class="txt" id="merchant_to_create" value="{{ Input::old('newmerchant') }}" autocomplete="off" />
		        </div> 
			 	
			 	<label>Currency <span class="mand">*</span></label>		        
		        <select id="the_currency" name="currency_code" class="sel" <?php echo $preferences->currency_code != null || $preferences->currency_code != "" ? "": "";  ?>>
			  		<?php if($preferences->currency_code == null || $preferences->currency_code == ""): ?>
				    <option value="" selected="selected">Select Currency</option>
				    <?php endif; ?>
				    <?php echo IntegrityInvoice\Utilities\AppHelper::getUserCurrencyListOptionsForExpense($currency_list, $preferences->currency_code); ?>				
				      
				</select>
					 
	   	 		<label>Ref. <span>e.g. Receipt or Invoice ID</span></label>
	            <input type="text" name="ref" class="txt" id="ref" value="{{ Input::old('ref')}}" autocomplete="off" />
	   	 	    
	   	 	    <p>Attach file.  
				    <input type="file" name="file" class=""><br />
				  <small>Acceptable formats: .doc .docx .xls .pdf .ppt .png .jpg .zip  
				 Max attachment size: 2MB </small></p>
		      </div><!-- END right_side -->
		      
		      <div class="submit_clear">
		       <br /><input type="submit" id="addexpense" class="gen_btn" name="addexpense" value="Create expense" />
		       @include('common.mandatory_field_message')
		      </div><!-- END submit_clear -->
	  	    
	   </div><!-- END two_sides -->
   
</div><!-- END add_expense_form -->

	<?php $dateformat ="d/m/Y";
		// British dateformat
		if($preferences->date_format == "dd/mm/yyyy"){ $dateformat ="d/m/Y"; }
		
		// America dateformat
		if($preferences->date_format == "mm/dd/yyyy"){ $dateformat = "m/d/Y"; }	?>	
    <input type="hidden" class="pref_dateformat" value="{{ $dateformat }}" >

{{ Form::close() }}

<?php else: ?>
	<h3>You have reached your monthly limit. Please consider upgrading your account if you wish to add more expenses.</h3>
	<p><a href="{{ URL::to('subscriptions') }}" class="btn"> UPGRADE NOW</a></p>
	
<?php endif; ?>

  @stop
  

	@section('footer')
 
		<script src="{{ URL::asset('assets/js/jquery.datetimepicker.js') }}"></script>
		
		 <script>
	  	
	  		$(document).ready(function() {	
	  			
	  			if($('#appmenu').length > 0){
				    
		  		  $('.create_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_create_expense').addClass('selected');		  		
		  		  $('.create_all_menu ul').css({'display': 'block'});	
		  		  			  		
			    }

                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                    $('#the_currency, #category, #merchant').select2({ width: 'element' });
                }
				
				 
	  			$('#issuedate').datetimepicker({			 
					lang:'en',
					timepicker:false,
					format: $('.pref_dateformat').val(),
					formatDate:'Y/m/d',
					closeOnDateSelect:true					  
				}); 
	  			
	  		 
				$('.newMerchant').hide();
				
				$('#merchant').on('change', function() {
					
				   if($(this).val() == "newmerchant"){
				   		
				   		$('.newMerchant').fadeIn();
				   		
				   }else{
				   	
				   		$('.newMerchant').fadeOut();
				   }
				 
				   
				});
	  			
	  			  			 
					
		   		$('input[type=submit]').click(function(){	
				 
					
					if($.trim($('#note').val()) == ""){						
						alert('Enter a description');						
						return false;
					}
					
					if($.trim($('#amount').val()) == ""){						
						alert('Enter the amount');						
						return false;
					}
					 
					if($.trim($('.issuedate').val()) == ""){						
						alert('Select the date');						
						return false;
					}
					
					if($.trim($('#merchant').val()) == ""){						
						alert('Select or Enter Suppler / Seller name');						
						return false;
					}
					
					if($.trim($('#merchant').val()) == "newmerchant"){
						
						if($.trim($('#merchant_to_create').val()) == ""){
							alert('Enter new merchant\'s name');						
							return false;
						}
				   	 
				    }
					
				});
	
	  		});
	  		
	  </script>
 
	@stop