@if($errors->has())
<div class="flash error">
	<ul>
		{{ $errors->first('company', '<li>:message</li>') }}
		{{ $errors->first('firstname', '<li>:message</li>') }}		 
		{{ $errors->first('email', '<li>:message</li>') }}
	</ul>
</div>
@endif  