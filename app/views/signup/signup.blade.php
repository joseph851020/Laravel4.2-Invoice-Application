@extends('layouts.signup')

	@section('content')
	 
	@if(isset($_SESSION['trc']) && $_SESSION['trc'] != "")
		$referrer = $_SESSION['trc'];
	@elseif(isset($_COOKIE['trc']) && $_COOKIE['trc'] != ""){
		$referrer = $_COOKIE['trc'];
	@endif
	
	<div id="signup-wrap">
    
    <a href="http://www.sighted.com"><img src="{{ URL::asset('integritylogo.png') }}" alt="Integrity Invoice" style='width:190px;height:86px;'></a>
	<h1>Signup takes 20 sec. <span>No setup fees. No contract.</span></h1>
	
	<div class="a_error"></div>
	 
		@if($errors->has())
		<div class="flash error msg_error">
		<ul>		 
			{{ $errors->first('email', '<li>:message</li>') }}
			{{ $errors->first('password', '<li>:message</li>') }}	
		</ul>
		</div>		 
		@endif 
		
		@if (Session::get('failed_flash_message'))
			<div class="flash error msg_error">{{ Session::get('failed_flash_message') }}</div>
		@endif 	
	
	{{ Form::open(array('route' => 'register', 'method' => 'post', 'class' => '') ) }}	
		
	<div id="msgbox" style="display:none"></div><!-- END Check if user exists -->
	
	<div id="signupform">
	   
	   <div class="form_row">
		    <label>Email address</label>
		    {{ Form::text('email', Input::old('email'), array('id' => 'email', 'autocomplete' => '')) }}
	   </div>
   
  		<div class="form_row">
		    <label>Password<span class="small">Minimum 6 characters</span></label>
		    {{ Form::password('password', array('id' => 'password')) }}
		 </div> 
	 
	   <div class="form_row password_meter">
	   	
		    <div id="iSM">
		    	<p><small>Password strength meter</small></p>
		        <ul class="weak"><li id="iWeak">Weak</li><li id="iMedium">Medium</li><li id="iStrong">Strong</li></ul>
		    </div>
		    <div class="spacer"></div>
		     
	   </div>
   		
   	   <div class="form_row">
   	   		<p class="referral_text">Got a referral code? <a class="reveal_referral" href="">click here</a></p>
   	   		<div class="referral_code_section">
		    	<label>Referral code</label>
		    	<input type="text" name="referral_code" class="referral_code" id="referral_code" value="{{ Input::old('referral_code') }}">	 
		    </div>
	   </div>
     
	   <div class=""> 
	   	 
			 <?php $plan = Request::segment(2); 
		  		  if($plan == null || !is_numeric($plan))
				  $plan = 0; ?>
			  
			{{ Form::hidden('selected_plan', $plan, array('id' => 'acc'))}}
		 
	    </div>
	 
	    <div class="form_row signupbtn">	    
		    <input type="hidden" name="referrer" id="referrer" value="{{ $track = isset($referrer) ? $referrer : "" }}" >
		    <input type="submit" name="submit" class="gen_btn" id="provision" value="Create your account">		 
		</div>
		
		<p class=""><span class="agree_text">By clicking the signup button above you agree to the <a href="http://www.integrityinvoice.com/terms-of-service" target="_blank">Terms of use</a> and <a href="http://www.integrityinvoice.com/privacy" target="_blank">Privacy policy</a> of the website.
		    	</span></p> 
	
	<div> <!-- END signupform -->
	
    
	{{ Form::close() }}
	
 </div><!-- END signup-wrap -->
 
    
@stop