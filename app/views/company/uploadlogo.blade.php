@extends('layouts.default')

	@section('content')
	 
	  <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; <a href="{{ URL::to('company') }}">Business Profile</a> &raquo; logo</h1>
	 
	  {{  Form::open(array('url' => 'company/uploadlogo', 'files' => true)) }}
	  
	  	
  	@if($errors->has())
  	  <div class="flash error">
		<ul>
			{{ $errors->first('file', '<li>:message</li>') }}
		</ul>
	  </div>
	@endif 
	   
	   <div id="company_logo">
	   	
	   	<div class="thelogo">
	   		
	   		<?php  $tenantID = Session::get('tenantID'); $ext = '.png'; $logo_file =  public_path(). '/te_da/'.$tenantID . '/'.$tenantID.$ext; ?>
			 
			@if (file_exists($logo_file))					 
				 <img class="gen_logo_size" src="{{ Config::get('app.app_main_domain').'/te_da/'.$tenantID.'/'.$tenantID.'.png' }}" alt="" />
			@endif			
	   		
	   	</div><!-- END thelogo -->
	   	
        <div class="longbox">
        	
            <label>Logo file: </label>	            
            {{ Form::file('file') }}
            
            <p><span class="small">(For best result use pixel dimensions of 500px (width) and 250px (height). Smaller size will also be uploaded.  JPG or PNG are the acceptable formats) </span></p>
            <input type="submit" id="uploadlogo" class="gen_btn" name="uploadlogo" value="Upload" />
        	 
        </div><!-- END LONG BOX -->
        
	  </div><!-- END company logo  -->
	
	{{ Form::close() }}  
			 
	@stop