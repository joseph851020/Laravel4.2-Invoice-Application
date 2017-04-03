@extends('layouts.default')

	@section('content')
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Business Profile</h1>
	
	{{ Form::open(array('url' => 'company/update', 'method' => 'PUT')) }}
  	
   @include('common.company_errors')
	
   <div id="company_profile">
	   	
        <div class="longbox">
            <label>Business / Company name <span class="mand">*</span>
            </label>
            <input type="text" name="company_name" class="txt" value="<?php echo $company->company_name; ?>" />
            
            <label>Address line 1 </label>
           <input type="text" name="add_1" class="txt" value="<?php echo $company->add_1; ?>" />
            
            <label>Address line 2 </label>
           <input type="text" name="add_2" class="txt" value="<?php echo $company->add_2; ?>" />
            
            <label>City</label>
           <input type="text" name="city" class="txt" value="<?php echo $company->city; ?>" />
            
            <label> County or State</label>
            <input type="text" name="state" class="txt" value="<?php echo $company->state; ?>" />
            
            <label>Postcode or zip code</label>
            <input type="text" name="postal_code" class="txt" value="<?php echo $company->postal_code; ?>" />
            
        </div><!-- END LONG BOX -->
       
       <div class="longbox">
  
			<label>Country </label>
	            <select name="country" id="country" class="sel">
				    <option value="<?php ?>" selected="selected">- select country -</option>
				    <?php foreach($countries as $country): ?>
				        <option <?php echo $company->country == $country->name ? "selected=\"selected\"" : ""; ?> value="<?php echo $country->name; ?>"><?php echo $country->name; ?></option>
				    <?php endforeach; ?>
				</select>
			       
            <label>Telephone</label>
            <input type="text" name="phone" class="txt" value="<?php echo $company->phone; ?>" />
            
            <label>Fax</label>             
            <input type="text" name="fax" class="txt" value="<?php echo $company->fax; ?>" />
            
            <label>Email <span class="mand">*</span>
            </label>
            <input type="text" name="email" class="txt" value="<?php echo $company->email; ?>" />
            
            <label>Website</label>
            <input type="text" name="website" class="txt" value="<?php echo $company->website; ?>" />         

            <div class="spacer"></div>
        	                       
	        <input type="hidden" name="module" value="settings"/>
	        <input type="hidden" name="action" value="update_company_details"/>
	        <input type="submit" class="gen_btn sb" name="update_company_details" value="Update profile" />
	        
	        @include('common.mandatory_field_message')
 
           </div> <!-- END LONG BOX -->
           
           <div class="thelogo">
	   		
	   			<?php  $tenantID = Session::get('tenantID'); $ext = '.png'; $logo_file =  public_path(). '/te_da/'.$tenantID . '/'.$tenantID.$ext; ?>
			 
				@if (file_exists($logo_file))					 
					 <img class="gen_logo_size"src="{{ Config::get('app.app_main_domain').'/te_da/'.$tenantID.'/'.$tenantID.'.png' }}" alt="" />
					<br /> <a class="btn" href="{{URL::to('company/logo')}}">Change Logo</a>
				@else
				 <a class="btn" href="{{URL::to('company/logo')}}">Upload Logo</a>			
				@endif			
	   		
	   		</div><!-- END thelogo -->
       
      		 <div class="company_bottom_section">	   				        
		        	<p style="font-size:14px; line-height:25px; margin:10px 0 0 0; font-weight:normal;"> App ID: <span style="color:#252424; font-weight:bold; ">{{ Session::get('tenantID') }}</span> <br /> <span>This is required for technical support and API calls.</span></p>
		       		<!-- <p style="font-size:14px; line-height:25px; margin:10px 0 0 0; font-weight:normal;"> Referral code: <span style="color:#252424; font-weight:bold; ">{{ $referral_code }}</span><br /> <span>You Get one month FREE premium subscription anytime someone signs up using your referral code. </span> </p>  -->
		       		<p>If you wish to cancel your account please click --        			
       			<a class="ordinary_link" href="{{ URL::to('company/cancel') }}">Request cancellation</a>
       		 </p>
    		</div>
    		
   			 
        
      </div><!-- END company_profile -->
	
	{{ Form::close() }}
		 
	@stop
	

	@section('footer')
	
	 	<script>
       		$(document).ready(function() {
       			
       			if($('#appmenu').length > 0){
				 
				  $('.settings_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_company').addClass('selected');		  		
		  		  $('.settings_all_menu ul').css({'display': 'block'});
			     }
			     
			     $('#country').select2({ width: 'element' });
			 
      
				$('input[type=submit]').click(function(){	
				 
					if($.trim($('#country').val()) == ""){						
						alert('Country field cannot be blank.');						
						return false;
					}
					
				});
				
			});
 
        </script>
	  
	@stop
	


@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#menu').length > 0){
				  $('#menu').multilevelpushmenu('expand', 'Products');				 
				  $('.menu_export_products').addClass('selected');
			 }
			 
		});
		
	</script>
 
	@stop