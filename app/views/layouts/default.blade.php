@include('layouts/header')
 
   	
	@if (Session::get('flash_message'))
		<div class="flash success">{{ Session::get('flash_message') }}</div>
	@endif
	
	@if (Session::get('failed_flash_message'))
		<div class="flash error">{{ Session::get('failed_flash_message') }}</div>
	@endif
	
	@yield('content')
	
 	<?php if(str_contains(Request::url(), array('dashboard','company', 'client', 'video'))): ?>
 	<div class="upgrade_notify">
	<?php 
		$acc_plan = Tenant::where('tenantID','=', Session::get('tenantID'))->first(); 
		if( $acc_plan->account_plan_id < 2): ?>
		<p class="free_to_premium_message">You're using free account, <a href="{{ URL::route('subscription') }}">upgrade</a> to access more features. </span>
			<span>Get 1 month of premium subscription (for FREE) for every friend that signs up with your referral code below: </span>
			<span class="ref_code"><?php echo $acc_plan->referral_code; ?></span>
		</p>
	<?php elseif($acc_plan->account_plan_id > 1 && $acc_plan->status < 1): ?>
		<p class="free_to_premium_message">Your subscription has expired, <a href="{{ URL::route('subscription') }}">renew now</a>. </span>
			<span>or Get 1 month of premium subscription (for FREE) for every friend that signs up with your referral code below: </span>
			<span class="ref_code"><?php echo $acc_plan->referral_code; ?></span>
		</p>
	<?php endif; ?>
	</div><!-- END upgrade_notify -->
	
  <?php endif; ?>
	
</div>  <!-- End panel -->   
  
 </div> <!-- End pagebody -->
 		
 
@include('layouts/menu')
   
@include('layouts/footer')	
       