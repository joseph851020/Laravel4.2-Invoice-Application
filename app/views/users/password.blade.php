@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Update Password</h1>
	
	{{ Form::open(array('url' => 'user/password/update', 'method' => 'PUT')) }}
	<div id="new_client_form">

		 @include('common.user_password_errors')
		
	       <div class="longbox">
	       	
	       	<label>Current Password<span class="mand">*</span></label>
		            <input type="password" name="current_password" class="txt" id="" value="{{ Input::old('current_password')}}" />
		          
				<label>New Password<span class="mand">*</span></label>
		            <input type="password" name="password" class="txt" id="" value="{{ Input::old('password')}}" />
		        <label>Confirm password <span class="mand">*</span></label>
		            <input type="password" name="confirm_password" class="txt" id="confirm_password" value="{{ Input::old('confirm_password') }}" />  
		         <input type="hidden" name="userId" value="{{ $user->id }}" /><br />
			     <input type="submit" id="addnewuser" class="gen_btn" name="edit_password" value="Change password" />
		      
		   </div><!-- END Long box -->	
		 
		</div><!-- END new_user_form form -->
	{{ Form::close() }}
 
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