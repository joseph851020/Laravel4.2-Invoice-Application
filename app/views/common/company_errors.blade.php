@if($errors->has())
<div class="flash error">
	<ul>
		{{ $errors->first('company_name', '<li>:message</li>') }}
		{{ $errors->first('email', '<li>:message</li>') }}
	</ul>
</div>
@endif 