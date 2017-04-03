@extends('layouts.default')

	@section('content')
	 
	 <h1>Recommend Integrity Invoice to your friends</h1>
	<div class="" class="group form">
	  
    	<h4>Share and recommend</h4>
    	
    	<div id="fb-root"></div>
			<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			  fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));</script>
			
		 <div class="fb-like" data-href="http://www.integrityinvoice.com" data-send="true" data-width="150" data-show-faces="false" data-action="recommend" data-font="arial"></div>
	 
	   
		 <h4>Send invites email to your friends</h4>
		
		 {{ Form::open(array('url' => 'dashboard/send_invite', 'method' => 'POST')) }}
		  
			 @if($errors->has())
			 <ul>
				{{ $errors->first('feedback_type', '<li>:message</li>') }}
				{{ $errors->first('issues', '<li>:message</li>') }}
			 </ul>
			 @endif  
		
 
		<label>First Friend's email address<span class="mand"> (*)</span></label>
            <input type="text" name="email1" class="txt" id="" value="{{ Input::old('email1') }}" />
            
         <label>Second Friend's email address<span> (optional) </span></label>
            <input type="text" name="email2" class="txt" id="" value="{{ Input::old('email2') }}" />
            
         <label>Third Friend's email address<span> (optional) </span></label>
            <input type="text" name="email3" class="txt" id="" value="{{ Input::old('email3') }}" />
       
       <label>Message <span> (You may edit as appropiate) </span></label>
            <textarea id="invite_message" name="invite_message" class="txtarea"><?php echo str_replace("<br />","\n" , str_replace("<br />","\r\n", 'Hi, <br />
            <br />I recently signed up for this great business web application for managing all my invoices, expenses and receipts, and helping me to track my payments. It\'s called Integrity Invoice.<br /><br />It\'s great, so you should check it out! They have a free account which you can sign up for at http://www.integrityinvoice.com <br /><br />'.Session::get('firstname') . ' ' . Session::get('lastname'))); ?></textarea>

        <input type="submit" id="send_invites" class="gen_btn" name="send_invites" value="Send invite" />  
    
	     {{ Form::close() }}
	  
	</div><!-- END for_help -->
	  
			 
	@stop