@extends('layouts.sendmail')

	@section('content')
	 
	<h2>We're Sorry to See you Go!</h2>
	<p>Dear {{ $firstname }}, consider this email to be confirmation that your account has been cancelled, and all your Sighted information has been deleted permanently.</p>
	
	<p>We're hoping you'll come back soon because we will miss you!
		<br /><br />Kind regards,<br />Sighted Team</p>
	
	@stop
