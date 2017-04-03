@if($errors->has())
	<div class="flash error">
	<ul>
		{{ $errors->first('amount', '<li>:message</li>') }}
		{{ $errors->first('created_at', '<li>:message</li>') }}			
		{{ $errors->first('category_id', '<li>:message</li>') }}
	</ul>
	</div>
@endif  