@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; User Profile</h1>
	
	{{ Form::open(array('url' => 'user/update', 'method' => 'PUT')) }}
	<div id="new_client_form">

		 @include('common.user_edit_errors')
		
	    <div class="longbox">

				<label>First name <span class="mand">*</span></label>
		            <input type="text" name="firstname" class="txt" id="firstname" value="{{ $user->firstname }}" />
		        <label>Last name</label>
		            <input type="text" name="lastname" class="txt" id="firstname" value="{{ $user->lastname }}" />
		        <label>Username</label>
		            <input type="text" name="username" class="txt" id="username" value="{{ $user->username }}" />
		       <label>Email address<span class="mand">*</span></label>
		            <input type="text" name="email" class="txt" id="email" value="{{ $user->email }}" />	
		            
		       <label>Phone</label>
		            <input type="text" name="phone" class="txt" id="phone" value="{{ $user->phone }}" />

                <div>
                <label class="inline_label" for="notify">Want New Feature Notifications?</label>
                {{ Form::checkbox('notify',  null, $user->notify, ['id' => 'notify']) }}
		        </div>
		         <input type="hidden" name="userId" value="{{ $user->id }}" /><br />
			     <input type="submit" id="addnewuser" class="gen_btn" name="edit_user" value="Save" />
			     
			     <br /> <a class="gen_btn" href="{{ URL::to('user/password') }}">Change password here</a>
		         
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