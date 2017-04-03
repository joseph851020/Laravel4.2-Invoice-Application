@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('clients', 'Clients', array(), array('class' => 'to_all')) }} &raquo; New</h1>
<?php if($limitReached == FALSE): ?>

	{{ Form::open(array('url' => 'clients/store', 'method' => 'POST')) }}
	<div class="new_client_form">

		 @include('common.client_errors')
		
	    <div class="longbox">

				<label>Company / Business name <span class="mand">*</span></label>
		            <input type="text" name="company" class="txt the_company_name" id="company_" value="{{ Input::old('company')}}" />
		            <p class="show_hide_field ordinary_link2"><a class="ordinary_link" href="">Show address and other fields</a></p>
		        <div class="hide_field">
		        <label>Address Line 1 <span> </span></label>
		            <input type="text" name="add_1" class="txt" id="add_1" value="{{ Input::old('add_1')}}" />
		        <label>Address line 2<span> </span></label>
		            <input type="text" name="add_2" class="txt" id="add_2" value="{{ Input::old('add_2')}}" />
		        <label>City<span> </span></label>
		            <input type="text" name="city" class="txt" id="city" value="{{ Input::old('city')}}" />
		        <label>County <span>(or state)</span></label>
		            <input type="text" name="state" class="txt" id="state" value="{{ Input::old('state')}}" />
		        </div>
		   </div><!-- END Long box -->	
		  
		   <div class="hide_field longbox">
		   	
		   	    <label>Postcode <span>(or Zip code)</span></label>
		            <input type="text" name="postal_code" class="txt" id="postal_code" value="{{ Input::old('postal_code')}}" />
		        <label>Country</label>
		         <select name="country" id="country" class="sel">
				    <option value="<?php ?>" selected="selected">- select country -</option>
				    <?php foreach($countries as $country): ?>
				        <option value="<?php echo $country->name; ?>"><?php echo $country->name; ?></option>
				    <?php endforeach; ?>
				</select>
				 
		        <label>Tax id <span>(if appropiate)</span></label>
		            <input type="text" name="tax_id" class="txt" id="tax_id" value="{{ Input::old('tax_id')}}" />      
		        <label>Note <span> (for personnel use only) </span></label>
		            <textarea id="notes" name="notes" class="txtarea">{{ Input::old('note')}}</textarea>  <br /> 
		   	   <!--- <input type="submit" id="addnewclient_1" class="gen_btn" name="add_item" value="Save" />  -->

		   </div><!-- END Long box -->
		  
		</div><!-- END new_user_form form -->
		<p class="hide_hide_field ordinary_link2">&nbsp;&nbsp;&nbsp;<a class="ordinary_link" href="">Hide address and other fields</a></p> 
	  <div class="new_client_form primary_contact">	
	  		 <p class="padleft10"><strong>Contact Person 1</strong> </p>
		   
			<div class="longbox">
 				
		  		<label>First name<span class="mand">*</span> </label>
		            <input type="text" name="firstname" class="txt" id="firstname" value="{{ Input::old('firstname')}}" />
		        <label>Last name <span>(or surname)</span></label>
		            <input type="text" name="lastname" class="txt" id="lastname" value="{{ Input::old('lastname')}}" />
		        
	   		</div><!-- END Long box -->	
	   		<div class="longbox">
	   			<label>Email <span class="mand">*</span></label>
		            <input type="text" name="email" class="txt contact_email" id="email_" value="{{ Input::old('email')}}" />
		        <label>Telephone </label>
		         <input type="text" name="phone" class="txt" id="phone" value="{{ Input::old('phone')}}" />
		          <br /> <input type="submit" id="addnewclient" class="gen_btn" name="add_item" value="Create" />
		            @include('common.mandatory_field_message')
		           <p><a class="ordinary_link addsecondary_contact" href="">Add secondary contact</a></p>
	   		</div><!-- END Long box -->	
 			 
	   	
	</div><!-- END new_client_form -->	
	
	 
	<div class="new_client_form secondary_client">	
	  		 <p class="padleft10"><strong>Contact Person 2</strong> (secondary) Optional</p>
		   
			<div class="longbox">
 				
		  		<label>First name</label>
		            <input type="text" name="firstname_secondary" class="txt" id="firstname" value="{{ Input::old('firstname_secondary')}}" />
		        <label>Last name <span>(or surname)</span></label>
		            <input type="text" name="lastname_secondary" class="txt" id="lastname" value="{{ Input::old('lastname_secondary')}}" />
		        
	   		</div><!-- END Long box -->	
	   		<div class="longbox">
	   			  <label>Email</label>
		            <input type="text" name="email_secondary" class="txt" id="email_" value="{{ Input::old('email_secondary')}}" />
		        <label>Telephone<span></span></label>
		            <input type="text" name="phone_secondary" class="txt" id="phone" value="{{ Input::old('phone_secondary')}}" />
		          <br /> <input type="submit" id="addnewclient" class="gen_btn" name="add_item" value="Create" />
		        
	   		</div><!-- END Long box -->	
 			 
	</div><!-- END new_client_form -->	
	
	 
	{{ Form::close() }}

<?php  else: ?>
	<h3>You have reached your limit. Please consider upgrading if you wish to add more clients.
		{{ HTML::linkRoute('subscription', 'UPGRADE NOW', array(), array('class' => 'to_all')) }}
	</h3>
	
<?php endif; ?>

@stop

@section('footer')

		<script>
		
			$(function(){
			 
			 	  if($('#appmenu').length > 0){
			 	  	
			 	  	  $('.create_all_menu').addClass('selected_group'); 		 
			  		  $('.menu_create_client').addClass('selected');		  		
			  		  $('.create_all_menu ul').css({'display': 'block'});
			  		 
				  }

                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) == false ) {
                    $('#country').select2({ width: 'element' });
                }

				  $('.show_hide_field').click(function() {
				 	$('.show_hide_field').hide();				 
				  	$('.hide_field').fadeIn(400, function(){				  		
				  		$('.hide_hide_field').show();
				  	});
				  	
				  	return false;				   
				 });
				 
				 
				 $('.hide_hide_field').click(function() {				 
				  	$('.hide_field').fadeOut(400, function(){
				  		$('.show_hide_field').show();
				  		$('.hide_hide_field').hide();
				  	});
				  					  	
				  	return false;				   
				 });
				 
				 
				
				$('.addsecondary_contact').click(function() {
					$('.addsecondary_contact').hide();
				  	$('.secondary_client').fadeIn();
				  	return false;
				   
				});
				
				
				
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