@extends('layouts.sendmail')
	
	@section('content')
		<h2>Password Reset</h2>

		<div>
			To reset your password, complete this form: <a class="btn" href="{{ URL::to('passwordresets/reset', array($type, $token)) }}"> click here</a>
		</div>
   @stop