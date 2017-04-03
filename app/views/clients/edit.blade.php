@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::to('clients') }}">Clients</a> &raquo; Edit: {{ $client->company }}</h1>
	
	{{ Form::open(array('url' => 'client/update', 'method' => 'PUT')) }}
	
	<div class="edit_client_form">
		
		 @include('common.client_errors')
   
	    <div class="longbox">
		    	<label>Company / Business name <span class="mand">*</span></label>
		            <input type="text" name="company" class="txt" id="company_" value="{{ $client->company }}" />
		        <label>Address Line 1 <span> </span></label>
		            <input type="text" name="add_1" class="txt" id="add_1" value="{{ $client->add_1 }}" />
		        <label>Address line 2<span> </span></label>
		            <input type="text" name="add_2" class="txt" id="add_2" value="{{ $client->add_2 }}" />
		        <label>City<span> </span></label>
		            <input type="text" name="city" class="txt" id="city" value="{{ $client->city }}" />
		        <label>County <span>(or state)</span></label>
		            <input type="text" name="state" class="txt" id="state" value="{{ $client->state }}" />
		            
		   </div><!-- END Long box -->	
		  
		   <div class="longbox">
		   	
		        <label>Postcode <span>(or Zip code)</span></label>
		            <input type="text" name="postal_code" class="txt" id="postal" value="{{ $client->postal_code }}" />
		        <label>Country</label>		            
		         <select name="country" id="country" class="sel">
				    <option value="<?php ?>" selected="selected">- select country -</option>
				    <?php foreach($countries as $country): ?>
				        <option <?php echo $client->country == $country->name ? "selected=\"selected\"" : ""; ?> value="<?php echo $country->name; ?>"><?php echo $country->name; ?></option>
				    <?php endforeach; ?>
				</select>   
		       <label>Tax id <span>(if appropiate)</span></label>
		            <input type="text" name="tax_id" class="txt" id="tax_id" value="{{ $client->tax_id }}" />      
		        <label>Note <span> ( for personal use only) </span></label>
		            <textarea id="notes" name="notes" class="txtarea">{{ $client->notes; }}</textarea>  		            
		           
		   </div><!-- END Long box -->	
		  
	</div><!-- END edit_client_form form -->
	
	<div class="edit_client_form primary_contact">	
	  		 <p><strong>Contact Person 1</strong> </p>
		   
			<div class="longbox">
	  
		  		<label>First name<span class="mand">*</span></label>
		            <input type="text" name="firstname" class="txt the_company_name" id="firstname" value="{{ $client->firstname }}" />
		        <label>Last name <span>(or surname)</span></label>
		            <input type="text" name="lastname" class="txt" id="lastname" value="{{ $client->lastname }}" />
		            
		     </div><!-- END Long box -->	
	   		<div class="longbox">
	   			
		        <label>Email <span class="mand">*</span></label>
		            <input type="text" name="email" class="txt contact_email" id="email_" value="{{ $client->email }}" />
		        <label>Telephone<span></span></label>
		            <input type="text" name="phone" class="txt" id="phone" value="{{ $client->phone }}" />
		         
			     <input type="hidden" name="clientId" value="{{ $client->id }}" />
			     
			     <br />  <input type="submit" id="editclient_2" class="gen_btn" name="edit_item" value="Save" />
			     @include('common.mandatory_field_message')
			     
			</div><!-- END Long box -->	
 		 
		</div><!-- END edit_client_form -->	
		<?php if($client->firstname_secondary == ""  || $client->firstname_secondary == NULL): ?>	
			<a class="btn addsecondary_contact" href="">Add secondary contact</a>
		<?php endif; ?>
	 
		 <div class="edit_client_form secondary_client_edit <?php echo $client->firstname_secondary == "" || $client->firstname_secondary == NULL ? "hide_secondary" : ""; ?>">	
	  		 <p><strong>Contact Person 2</strong> (secondary) Optional</p>
		   
			<div class="longbox">
 				<label>First name</label>
		            <input type="text" name="firstname_secondary" class="txt" id="firstname" value="{{ $client->firstname_secondary }}" />
		        <label>Last name <span>(or surname)</span></label>
		            <input type="text" name="lastname_secondary" class="txt" id="lastname" value="{{ $client->lastname_secondary }}" />
		        
	   		</div><!-- END Long box -->	
	   		<div class="longbox">
	   		 
		        <label>Email</label>
		            <input type="text" name="email_secondary" class="txt" id="email_" value="{{ $client->email_secondary }}" />
		        <label>Telephone<span></span></label>
		            <input type="text" name="phone_secondary" class="txt" id="phone" value="{{ $client->phone_secondary }}" />
		          <br /> <input type="submit" id="editclient" class="gen_btn" name="edit_item" value="Save" />
	   		</div><!-- END Long box -->	
 			 
		 </div><!-- END edit_client_form -->
	  
	{{ Form::close() }}
 
	@stop
	

@section('footer')

		<script>
		
			$(function(){
				
				if($('#appmenu').length > 0){
				    $('.manage_all_menu').addClass('selected_group'); 		 
			  		$('.menu_all_clients').addClass('selected');		  		
			  		$('.manage_all_menu ul').css({'display': 'block'});
			    }	

                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                    $('#country').select2({ width: 'element' });
                }
				 
				 
				if($('.hide_secondary').length > 0)
				{
					$('.hide_secondary').hide();
					
					$('.addsecondary_contact').click(function() {
						
					  	$('.hide_secondary').fadeIn();
					  	return false;
					   
					});
				
				}
				
				$('input[type=submit]').click(function(){	
				 
				 
					if($.trim($('.the_company_name').val()) == ""){						
						alert('Business / company name is required');						
						return false;
					}
					
					if($.trim($('#firstname').val()) == ""){						
						alert('Contact firstname is required');						
						return false;
					}
					
					if($.trim($('.contact_email').val()) == ""){						
						alert('Contact email is required');						
						return false;
					}
				 
				});
				
			});
			
		</script>
		
@stop