@if($errors->has())
	<div class="flash error">
	<ul>
		{{ $errors->first('unit_exchange_rate', '<li>:message</li>') }} 
	</ul>
	</div>
@endif  