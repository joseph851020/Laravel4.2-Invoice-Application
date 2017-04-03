@extends('layouts.default')

	@section('content')
	 
	<h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a>&raquo; Feedback &amp; Feature request / Problem(s) reporting</h1>
	<div class="for_help" class="group">
	    <div class="div1">
	        <p>Your feedback is very important to us.<br /><br />Please help us improve Integrity Invoice by reporting any issues or suggesting new features. Also don't hesitate to let us know how you would like your suggestion or idea implemented.
	        	 We will try to fix reported issues as soon as possible and you will be notified when it's done.  <br /><br />Thank you, Integrity Invoice Team.</p>
	    </div><!-- END div1 -->
	    
	    <div class="div2">
	  
	    {{ Form::open(array('url' => 'feedback/send', 'method' => 'POST')) }}
		 
		 
		 @if($errors->has())
		<ul>
			{{ $errors->first('feedback_type', '<li>:message</li>') }}
			{{ $errors->first('issues', '<li>:message</li>') }}
		</ul>
		@endif  
	
			<label>Subject / title<span>(of the issue)</span></label>
	            <input type="text" name="subject" class="txt" id="bug_subject" value="{{ Input::old('subject') }}" />
	         <label>Type<span> (Feature request or reporting a problem?)</span></label>
	            <select id="" name="feedback_type" class="sel">
	            	<option value="">-- please select --</option>
	                <option value="Feature request">Feature request</option>
	                <option value="Problem reporting">Problem reporting</option>            
	            </select>
	        <label>Prority<span> </span></label>
	            <select id="priority" name="priority" class="sel">
	            	<option value="">-- please select --</option>
	                <option value="high">High</option>
	                <option value="medium">Medium</option>
	                <option value="low">Low</option>                 
	            </select>
	       <label>Description <span> (Please describe in detail) </span></label>
	            <textarea id="bug_description" name="issues" class="txtarea">{{ Input::old('issues') }}</textarea>
	
	        <input type="submit" id="send_feedback" class="gen_btn" name="send_feedback" value="Send" />  
	     {{ Form::close() }}
	    </div><!-- END div2 -->
	</div><!-- END for_help -->
	  
			 
	@stop