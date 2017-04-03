@extends('layouts.default')

	@section('content')
	 
	 <h1><a class="do_previous" href="{{ URL::to('dashboard') }}">&nbsp;<i class="fa fa-home">&nbsp;</i></a> &raquo; <a href="{{URL::to('help') }}">Help</a> &raquo; Contact Us </h1>
	 <div class="more_space" class="group">
	   
	   <p> We will greatly appreciate your feedback to improve our system. If you have any problems please let us know. </p>
	 
	    {{ Form::open(array('url' => 'support/send', 'method' => 'POST')) }}
		 
		 
		 @if($errors->has())
		<ul>
			{{ $errors->first('feedback_type', '<li>:message</li>') }}
			{{ $errors->first('issues', '<li>:message</li>') }}
		</ul>
		@endif  
	
			<label>Subject / title<span class="mand">*</span></label>
	            <input type="text" name="subject" class="txt" id="bug_subject" value="{{ Input::old('subject') }}" />
	         <label>Type: Issue / Feature request <span class="mand">*</span></label>
	            <select id="" name="feedback_type" class="sel">
	            	<option value="">-- please select --</option>
	            	<option value="Problem reporting">Problem(s)</option>
	                <option value="Feature request">Feature request</option>	                            
	            </select>
	        <label>Prority</label>
	            <select id="priority" name="priority" class="sel">
	            	<option value="">-- please select --</option>
	                <option value="high">High</option>
	                <option value="medium">Medium</option>
	                <option value="low">Low</option>                 
	            </select>
	       <label>Description <span class="mand">*</span></label>
	            <textarea id="bug_description" name="issues" class="txtarea">{{ Input::old('issues') }}</textarea><br />
	
	        <input type="submit" id="send_feedback" class="gen_btn" name="send_feedback" value="Send" />  
	          @include('common.mandatory_field_message')
	     {{ Form::close() }}
	  
	</div><!-- END for_help -->
	  

@stop
	

@section('footer')

	<script>
	
		$(function(){
		 
		 	 if($('#appmenu').length > 0){
				    
				    $('.more_all_menu').addClass('selected_group'); 		 
			  		$('.menu_help').addClass('selected');		  		
			  		$('.more_all_menu ul').css({'display': 'block'});
			  }
		 
		});
		
	</script>
	
@stop