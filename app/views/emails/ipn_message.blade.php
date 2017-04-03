@extends('layouts.sendmail')

	@section('content')
	 
	<h2>New Instant Payment Notification</h2>
 
		<?php	
		$body  = 'An instant payment notification was successfully received from ';
		$body .= $ipn_data['payer_email'] . ' on '.date('m/d/Y') . ' at ' . date('g:i A') . "\n\n";
		$body .= " Details:\n";
	
		foreach ($ipn_data as $key=>$value){
			$body .= "\n$key: $value";
		}
		?>
		{{ $body }}
	 <br /> Sighted
	
	@stop
