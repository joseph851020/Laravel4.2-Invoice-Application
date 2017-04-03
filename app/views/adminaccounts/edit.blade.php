@extends('layouts.admin')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::previous() }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; User Profile</h1>
	
	{{ Form::open(array('url' => 'admin/accounts/update', 'method' => 'PUT')) }}
	<div id="new_client_form">

		 @include('common.user_edit_errors')
		
	    <div class="longbox">
			
			<label>Company name</label>
		            <input type="text" name="company_name" class="txt" id="firstname" value="{{$tenant->company->company_name}}" />
		        <!--label>User level</label>
		        	<select id="level" name="level" class="sel"> 
					<option <?php echo $tenant->level == 0 ? "selected" : ""; ?> value="2">Free</option>
					<option <?php echo $tenant->level == 1 ? "selected" : ""; ?> value="1">Preuium</option>
					<option <?php echo $tenant->level == 2 ? "selected" : ""; ?> value="0">Super Preuium</option>
	                      
				</select!-->    
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
			<label>Password</label>
		            <input type="password" name="password" class="txt" id="phone" value="{{ $user->password}}" />    
	
                <div>
                <!--label class="inline_label" for="notify">Want New Feature Notifications?</label!-->
                </div>
		             <input type="hidden" name="userId" value="{{ $user->id }}" /><br />

			     <input type="hidden" name="tenantID" value="{{ $user->tenantID}}" /><br />
			     <input type="submit" id="addnewuser" class="gen_btn" name="edit_user" value="Save" />
			     
			     <br /> <!--a class="gen_btn" href="{{ URL::to('user/password') }}">Change password here</a!-->
		         
		   </div><!-- END Long box -->	
		   
		  
		 
		</div><!-- END new_user_form form -->
	{{ Form::close() }}
 
  @stop
 
