@if($errors->has())
<div class="flash error">
	<ul> 
		{{ $errors->first('password', '<li>:message</li>') }}
		{{ $errors->first('confirm_password', '<li>:message</li>') }}
	</ul>
</div>
@endif  