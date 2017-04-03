@if($errors->has())
<div class="flash error">
	<ul>
		{{ $errors->first('firstname', '<li>:message</li>') }}
		{{ $errors->first('lastname', '<li>:message</li>') }}
		{{ $errors->first('username', '<li>:message</li>') }}
		{{ $errors->first('email', '<li>:message</li>') }}
		{{ $errors->first('password', '<li>:message</li>') }}
		{{ $errors->first('confirm_password', '<li>:message</li>') }}
	</ul>
</div>
@endif  