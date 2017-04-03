@extends('layouts.default')

<?php
use IntegrityInvoice\Utilities\AppHelper as AppHelper;
?>

	@section('content')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('expenses', 'Expenses', array(), array('class' => 'to_all')) }} &raquo; Edit
        @if($expense->recurring == 1)
        <span><i class="fa fa-repeat"></i> Recurring &nbsp;
            @if($expense->recur_status == 1)
                <span class="makeGreen">active</span>
             - next date: <?php $next_recur_date = $expense->recur_next_date;
            echo AppHelper::date_to_text($next_recur_date, $preferences->date_format);?></span>
        @else
        <span class="makeRed">inactive</span>
        @endif
      @endif
    </h1>

	{{ Form::open(array('url' => 'expense/update', 'method' => 'PUT', 'files'=>true)) }}

	@include('common.expense_errors')

	<input type="hidden" name="date_format" class="date_format" value="{{ $preferences->date_format }}" />
	<div id="add_expense_form">
		 <div class="two_sides">
	    	 <div class="left_side">
	    	   
	    	   <label>Date <span class="mand">*</span> <span>(select)</span></label>
		       <input type="text" name="created_at" class="txt issuedate" id="issuedate" value="{{ AppHelper::date_to_text(substr($expense->created_at, 0, 10), $preferences->date_format) }}" />
	   			 
	            <label>Category</label>
	            <select id="category" name="category_id" class="sel">	  
                 @foreach($categories as $category)
                    <option <?php echo $category->id == $expense->category_id ? 'selected="selected"' : ''; ?>  value="{{ $category->id }}">{{ $category->expense_name }}</option>
                 @endforeach 
	            </select>
	            
	            <label>Details  / Description <span class="mand">*</span> </label>
	    	    <textarea id="note" class="txtarea" name="note">{{ $expense->note }}</textarea>
	           
	            <label>Net Amount <span class="mand">*</span> <span>(e.g 75.99)</span></label>
	            <input type="text" name="amount" class="txt" id="amount" value="{{ $expense->amount }}" />
	           
	           </div><!-- END left_side -->
				
			 <div class="right_side">
			  
	     		<label>Merchant / Seller </label>
		         <select id="merchant" name="merchant_id" class="sel">
		         	<option value="newmerchant"> New merchant </option>               	                
	             	@foreach($merchants as $merchant)
	                <option <?php echo $merchant->id == $expense->merchant_id ? 'selected="selected"' : ''; ?> value="{{ $merchant->id }}">{{ $merchant->company }}</option>
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
				    <?php echo AppHelper::getUserCurrencyListOptionsForExpense($currency_list, $expense->currency_code); ?>				
				      
				</select>
				
				 
	   	 		<label>Ref. <span>e.g. Receipt or Invoice ID</span></label>
	            <input type="text" name="ref" class="txt" id="ref" value="{{ $expense->ref_no }}" />
	   	 	  
		        <input type="hidden" name="expenseId" value="{{ $expense->id }}" />
		        
		        	@if($expense->file != NULL && $expense->file != "")
					 <div>
					    <p><a class="ordinary_link2 status-bar2" href="{{ URL::to('expenses/'.$expense->id.'/download_file/') }}">File attached - <small>{{ AppHelper::decrypt($expense->file, $expense->tenantID) }}</small></a>
					    	&nbsp;<br /> <br /><a class="ordinary_link status-bar2" href="{{ URL::to('expenses/'.$expense->id.'/remove_file/') }}"><i class="fa fa-times"></i> remove file</a>
					    </p>
					 </div>
					@else
					
					 <p>Attach file.  
						  <input type="file" name="file" class=""><br />
						  <small>Acceptable formats: .doc .docx .xls .pdf .ppt .png .jpg .zip  
						 Max attachment size: 2MB </small></p>
				 
					@endif	

	 	   </div><!-- END two_sides -->
	  
	  	   <div class="submit_clear">
		      <br /><input type="submit" id="editexpense" class="gen_btn" name="editexpense" value="Save" />
		       @include('common.mandatory_field_message')
               <p><a href="" class="recurring_popup_open btn">{{ $expense->recurring == 1 ? "Edit recurring" : "Make recurring" }}</a><p/>

	  	    </div><!-- END submit_clear -->
   
	</div><!-- END add_expense_form -->

	<?php $dateformat ="d/m/Y";
			// British dateformat
			if($preferences->date_format == "dd/mm/yyyy"){ $dateformat ="d/m/Y"; }
			
			// America dateformat
			if($preferences->date_format == "mm/dd/yyyy"){ $dateformat = "m/d/Y"; }	?>	
        <input type="hidden" class="pref_dateformat" value="{{ $dateformat }}" >

{{ Form::close() }}


         <div id="recurring_popup" class="page_popup well">
             <h2>{{ $expense->recurring == 1 ? "Edit" : "Set" }} Recurring option</h2>
             <p>Auto-generate expense periodically.</p>
             {{ Form::open(array('url' => 'expenses/'.$expense->id.'/recurring', 'method' => 'POST')) }}

             <label>Next Recurring date</label>
             <input type="text" value="<?php echo $expense->recurring == 1 ? AppHelper::date_to_text(substr($expense->recur_next_date, 0, 10), $preferences->date_format) : ""; ?>" name="next_recurring_date" class="txt next_recurring_date" id="next_recurring_date" autocomplete="off" />

             <label>Frequency</label>
             <select id='recur_schedule' name='recur_schedule' class="sel">
                 <option <?php echo $expense->recur_schedule  == "" || $expense->recur_schedule == null ? "selected=selected": ""; ?> value="">Select</option>
                 <option <?php echo $expense->recur_schedule  == "Every week" ? "selected=selected": ""; ?> value="Every week">Every week</option>
                 <option <?php echo $expense->recur_schedule  == "Every two weeks" ? "selected=selected": ""; ?> value="Every two weeks">Every two weeks</option>
                 <option <?php echo $expense->recur_schedule  == "Every month" ? "selected=selected": ""; ?> value="Every month">Every month</option>
                 <option <?php echo $expense->recur_schedule  == "Every two months" ? "selected=selected": ""; ?> value="Every two months">Every two months</option>
                 <option <?php echo $expense->recur_schedule  == "Every three months" ? "selected=selected": ""; ?> value="Every three months">Every three months</option>
                 <option <?php echo $expense->recur_schedule  == "Every four months" ? "selected=selected": ""; ?> value="Every four months">Every four months</option>
                 <option <?php echo $expense->recur_schedule  == "Every six months" ? "selected=selected": ""; ?> value="Every six months">Every six months</option>
                 <option <?php echo $expense->recur_schedule  == "Every twelve months" ? "selected=selected": ""; ?> value="Every week">Every twelve months</option>
             </select>

             <label>Last Recurring Date</label>
             <input type="text" value="<?php echo $expense->recurring == 1 ? AppHelper::date_to_text(substr($expense->recurring_end_date, 0, 10),$preferences->date_format) : ""; ?>" name="last_recurring_date" class="txt last_recurring_date" id="last_recurring_date" autocomplete="off" />
             <br />

             <label class="make_inline_block">Active</label>
             <input type="checkbox" value="1" <?php if($expense->recur_status != 0){ echo "checked=\"checked\""; } ?> name="recur_status" id="recur_status" class="" /> &nbsp; &nbsp; &nbsp;
             <br />

             <button class="recurring_popup_close btn cancelBtn">Cancel</button> <input type="submit" id="update_recurring" class="gen_btn" name="" value="{{ $expense->recurring == 1 ? "Save" : "Activate recurring" }} " />
             @if($expense->recurring == 1)
             <a class="gen_btn" href="{{ URL::route('remove_expense_recurring', $expense->id) }}">Delete</a>
             @endif
             {{ Form::close() }}
         </div> <!-- END recurring_popup -->
 

  @stop
  

	@section('footer')
 
		<script src="{{ URL::asset('assets/js/jquery.datetimepicker.js') }}"></script>
        <script src="{{ URL::asset('assets/js/jquery.popupoverlay.js') }}"></script>
		
		 <script>
	  	
	  		$(document).ready(function() {	
	  			
	  			if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_expenses').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			    }

                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                    $('#the_currency, #category, #merchant').select2({ width: 'element' });
                }

                $.fn.popup.defaults.pagecontainer = '.page-panel';

                // Recurring Option
                $('#recurring_popup').popup({
                    opacity: 0.8,
                    vertical: 'top',
                    transition: 'all 0.3s',
                    outline: true, // optional
                    focusdelay: 300, // optional
                });

                if($('#next_recurring_date').length > 0)
                {
                    $('#next_recurring_date').datetimepicker({
                        lang:'en',
                        timepicker:false,
                        format: $('.pref_dateformat').val(),
                        formatDate:'Y/m/d',
                        minDate: 0,
                        closeOnDateSelect:true
                    });
                }

                if($('#last_recurring_date').length > 0)
                {
                    $('#last_recurring_date').datetimepicker({
                        lang:'en',
                        timepicker:false,
                        format: $('.pref_dateformat').val(),
                        formatDate:'Y/m/d',
                        minDate: 0,
                        closeOnDateSelect:true
                    });
                }


                //////  Submit Recurring
                $('#update_recurring').on('click', function(){

                    if($.trim($('#next_recurring_date').val()) == ""){
                        alert('Enter the next recurring date');
                        return false;
                    }

                    if($.trim($('#recur_schedule').val()) == ""){
                        alert('Select the Frequency of billing');
                        return false;
                    }

                    if($.trim($('#last_recurring_date').val()) == ""){
                        alert('Enter the last recurring date');
                        return false;
                    }

                });


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
	  			
	  			  			 
					
		   		$('#editexpense').click(function(){	
				 
					
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