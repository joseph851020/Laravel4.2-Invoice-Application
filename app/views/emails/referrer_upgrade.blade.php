@extends('layouts.sendmail')
 
	@section('content')
	  
	<p>Hi {{ $firstname }}, <p/>
	<?php if($former_plan_id < 2): ?>
	<p>Your account has been upgraded to a premium account by 1 month because a new user recently signed up with your referral code: <strong>{{ $referral_code }}</strong>.</p>
	 <?php else: ?>
	<p>Your account subscription has been extended for 1 month because a new user recently signed up with your referral code: <strong>{{ $referral_code }}</strong>.</p>
	<?php endif; ?>
	
	<p>We will continue to extend your subscription by 1 month (for FREE) for every friend that signs up with your referral code: <strong>{{ $referral_code }}</strong></p>
 
    <p>Kind regards, <br />Sighted Team</p>
 
	@stop