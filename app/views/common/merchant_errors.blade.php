@if($errors->has())
   <div class="flash error">
	<ul>
		{{ $errors->first('company', '<li>:message</li>') }}		 
	</ul>
  </div>
@endif  