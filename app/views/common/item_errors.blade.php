@if($errors->has())
 <div class="flash error">
	<ul>
		{{ $errors->first('item_name', '<li>:message</li>') }}
		{{ $errors->first('item_type', '<li>:message</li>') }}
		{{ $errors->first('unit_price', '<li>:message</li>') }}
	</ul>
  </div>
@endif  