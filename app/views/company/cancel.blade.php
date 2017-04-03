@extends('layouts.default')

	@section('content')
	 
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Cancel Account</h1>
	 
	  {{  Form::open(array('url' => 'account/cancel')) }}
	  
	  <p>Canceling your account will delete the account and all associated data permanently. Please enter your password to confirm.This action is irrecoverable, 
	  	if you have any issues we may be able to help resolve it by emaling us at info@sighted.com</p>
	  	
	  	<p>Please enter your password to confirm. </p>
	    <input type="password" name="password" value="" class="txt" />
	   <input type="hidden" name="cancel_token" value="{{ str_random(40); }}" />
	   
	   <input type="hidden" name="tenantID" value="{{ Auth::user()->get()->tenantID }}" />
	   <input type="hidden" name="super_user_id" value="{{ Auth::user()->get()->id }}" /><br />
	   <input type="submit" id="cancelaccount" class="gen_btn" name="cancelaccount" value="Cancel Account Permanently" />
	     
	{{ Form::close() }}  
			 
	@stop