@extends('layouts.default')

	@section('content')
	
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; {{ HTML::linkRoute('users', 'Users', array(), array('class' => 'to_all')) }} &raquo; create</h1>
	
<?php if($limitReached == FALSE): ?>

	{{ Form::open(array('url' => 'users/store', 'method' => 'POST')) }}
	<div id="new_client_form">

		 @include('common.user_errors')
		
	    <div class="longbox">

				<label>First name <span class="mand">*</span></label>
		            <input type="text" name="firstname" class="txt" id="firstname" value="{{ Input::old('firstname')}}" />
		        <label>Last name</label>
		            <input type="text" name="lastname" class="txt" id="firstname" value="{{ Input::old('lastname')}}" />
		        <label>Username<span class="mand">*</span></label>
		            <input type="text" name="username" class="txt" id="username" value="{{ Input::old('username')}}" />
		       <label>Email address<span class="mand">*</span></label>
		            <input type="text" name="email" class="txt" id="email" value="{{ Input::old('email')}}" />	
		         
		   </div><!-- END Long box -->	
		  
		   <div class="longbox">
		   			        
		  		<label>Phone</label>
		            <input type="text" name="phone" class="txt" id="phone" value="{{ Input::old('phone')}}" />
		        <label>Password<span class="mand">*</span></label>
		            <input type="password" name="password" class="txt" id="lastname" value="{{ Input::old('password')}}" />
		        <label>Confirm password <span class="mand">*</span></label>
		            <input type="password" name="confirm_password" class="txt" id="confirm_password" value="{{ Input::old('confirm_password')}}" />     
		       
		   </div><!-- END Long box -->
		   
		   
		</div><!-- END new_user_form form -->
		
		<br />&nbsp;&nbsp; <input type="submit" id="addnewuser" class="gen_btn" name="add_user" value="Create" />
	{{ Form::close() }}

<?php else: ?>
	<h3>You have reached your limit. Please consider upgrading if you wish to add more admin users.
		{{ HTML::linkRoute('subscription', 'UPGRADE NOW', array(), array('class' => 'to_all')) }}
	</h3>
	
<?php endif; ?>

	@stop
	
	@section('footer')
	
	 <script>
	
		$(document).ready(function(){
			
			if($('#appmenu').length > 0){				 
				  $('.settings_all_menu').addClass('selected_group'); 		 
		  		  $('.menu_all_users').addClass('selected');		  		
		  		  $('.settings_all_menu ul').css({'display': 'block'});
			 }
			 
		});
		
	</script>
 
	@stop