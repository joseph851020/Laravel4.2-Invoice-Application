@extends('layouts.default')

	@section('content')
 
 <div class="one_time_setting">
     
   	{{ Form::open(array('url' => 'settings/onetime', 'method' => 'put')) }}
   	
   	@if($errors->has())
	<div class="flash error">
		<ul>
			{{ $errors->first('currency', '<li>:message</li>') }}
			{{ $errors->first('date_format', '<li>:message</li>') }}
			{{ $errors->first('time_zone', '<li>:message</li>') }}
			{{ $errors->first('country', '<li>:message</li>') }}
			{{ $errors->first('business_model', '<li>:message</li>') }}
		</ul>
	</div>
	@endif 
	
	<h1 class="">One-Time Setup </h1>
 		<div id="progressbar">
	      <div class="progress_bar"></div><p class="progress_percentage"><span class="percentage_figure">1</span>% complete</p>
	   </div><!-- END progress bar-->
	
	<div class="onetime_part1">
 		
 		
		<table class="table">
				<tr>
					<td>
						<label>Business Name<span class="mand">*</span></label> 
						 <input name="company_name" id="company_name" class="txt" value="">					 
					</td>
				</tr>
				<tr>
					<td>
						<label>First name <span class="mand">*</span></label> 
						 <input name="firstname" id="firstname" class="txt" value="">					 
					</td>
				</tr>
				<tr>
					<td>
						<label>Last name</label> 
						 <input name="lastname" id="lastname" class="txt" value="">					 
					</td>
				</tr>
		 
		</table>
		
		<a class="btn gonext" href="#">Next</a>
		
	</div><!-- END onetime_part1 -->
		
		<div class="onetime_part2">		 
			
		   <table class="table">
			 
				<tr>
					<td>
						<label>What do you provide? (Products or Services)  <span class="mand">*</span></label>
						<select id="business_model" name="business_model" class="sel">            
			                <option value="">- select -</option>
			                <option value="1">Services</option>
			                <option value="0">Products </option>			                                 
		            	</select>
					</td>
				</tr>
				
				<tr class="bill_model">
					<td>
						<label>How do you bill?<span class="mand">*</span></label>
			            <select id="bill_option" name="bill_option" class="sel">
			            	<option value="">- select -</option> 
			            	<option value="1">Per Project</option>           
			                <option value="0">Per Hour</option>			                                 
			            </select>
		               <p>Don't worry you can always switch depending on the job.</p>
					</td>
				</tr>
				
				<tr>
					<td>
						<label>Home Currency <span class="mand">*</span></label>						 
						 <?php echo IntegrityInvoice\Utilities\AppHelper::getCurrencyList(); ?>	
					</td>
				
				</tr>
				
				<tr>
					<td>
						<label>Date format <span class="mand">*</span></label>
						<select id="date_format" name="date_format" class="sel">   
							<option value="">- select -</option>         
			                <option value="dd/mm/yyyy">Day / Month / Year</option>
			                <option value="mm/dd/yyyy">Month / Day / Year</option>		               
	            		</select>
					</td>
				</tr>
				 <tr>
					<td>
						<label>Industry</label>
						<?php echo IntegrityInvoice\Utilities\AppHelper::getIndustryList(); ?>						
					</td>
				</tr>
				
				 <tr>
					<td>
						<label>How did you hear about us?</label>
						 <input type="text" name="found_integrity" class="txt" value="" />					
					</td>
				</tr>
		  	  			
	    </table>
	    
	   
       <div class="btn-submit">
       	 <a class="btn goback" href="#">Back</a>
       	 <input type="hidden" name="theme_id" value="6">
         <input type="submit" id="update_prefs" class="update_prefs_btn btn" name="update_prefs" value="Finish &amp; Save" />
       </div><!-- END btn-submit -->
    
   </div><!-- END onetime_part2 -->
    
    {{ Form::close() }}
    
     <p><span class="mand">*</span> mandatory fields</p>
     
  </div><!-- END One time -->
 
	@stop


	@section('footer')
	
	 	<script>
       		$(document).ready(function() {

                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                    $('.one_time_setting select').select2({ width: 'element' });
                }

				 $('.percentage_figure').html(progress);
       			 
       			$('.company-name').text($('#company_name').val());
       		  
				$('#company_name').on('keyup', function(){
			      $('.company-name').text($(this).val());			     
			      	CheckIfChanged();
			    });
			    
			    $('#company_name').on('blur', function(){
			      $('.company-name').text($(this).val());
			      
			    });
			    
			    
			    $('.bill_model').hide();
			    
			    
			    
			    $('#business_model').on('keyup', function(){			      			     
			      	 
			    });
			    
			    $('#business_model').on('blur', function(){			      
			       
			    });
			    
			    $('#business_model').on('change', function() {
					
				   if($(this).val() == 1){
				   		
				   		$('.bill_model').fadeIn();
				   		
				   }else{
				   	
				   		$('.bill_model').fadeOut();
				   }
				 
				});
				    			
      
				$('#update_prefs').click(function(){	
					
					if($('#business_model').val() == ""){						
						alert('Please select what you provide i.e. products or services');
						return false;
					}
					
					
					if($('#business_model').val() == 1){	
						
						if($('#bill_option').val() == ""){
						
							alert('Since you provide services, please select how you bill, either per project or hour.');
							return false;
						
						}				
					 
					}
				 
					 
					if($('#currencylist').val() == ""){						
						alert('Please select your currency');						
						return false;
					}
					
					if($('#date_format').val() == ""){						
						alert('Please select your date format');
						return false;
					}
				 
				 
				});
				
				var $onetime_part1 = $('.onetime_part1');
				var $onetime_part2 = $('.onetime_part2');
				
				/////////////////////////////////////////////
				
				$('.gonext').click(function(){	
					
					if($('#company_name').val() == ""){						
						alert('Business / Company name field is required.');
						return false;
					}
					
					if($('#firstname').val() == ""){						
						alert('First name field is required.');
						return false;
					}
					 	
					$onetime_part1.slideUp();
					$onetime_part2.show();					
				});
				
				$('.goback').click(function(){					
					$onetime_part2.hide();
					$onetime_part1.slideDown();					
				});
				
				
				var progress = 0;
				var businessName = 0;
				var firstname = 0;
				var businessModel = 0;
				var currency = 0;
				var dateFormat = 0;
				
				
				CheckIfChanged();
				
				function CheckIfChanged() {
			        // do logic			        
			        
			         if($('#company_name').val() != ""){ businessName = 20; } else{ businessName = 0; }
			         if($('#firstname').val() != ""){ firstname = 20; } else{ firstname = 0; }
			         if($('#business_model').val() != "" && $('#business_model').val() != 1){ businessModel = 20; } else if($('#business_model').val() == 1 && $('#bill_option').val() != "" ){ businessModel = 20; } else { businessModel = 0; }
			         if($('#currencylist').val() != ""){ currency = 20; } else{ currency = 0; }
			         if($('#date_format').val() != ""){ dateFormat = 20; } else{ dateFormat = 0; }
			         			         
			         progress = businessName + firstname + businessModel + currency + dateFormat;
			         
				     $('.progress_bar').css({'width': (progress - 10) +"%" });
				     $('.percentage_figure').text(progress);
				     
				     progress = businessName + firstname;	
			
			        setTimeout(function () {
			            CheckIfChanged();
			        }, 1000);
			    }
				
			 
			    
			    // Function to update progress
			    
			    function updateProgress(){
			    	
			    }
				
				
			});
 
        </script>
	  
	@stop