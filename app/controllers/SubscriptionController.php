<?php
use IntegrityInvoice\Utilities\AppHelper;
use IntegrityInvoice\Billing\PaypalPaymentGateway;
use IntegrityInvoice\Mailers\BillingMailer;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\AccountPlanRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Repositories\DiscountRepositoryInterface;
use IntegrityInvoice\Repositories\CouponRepositoryInterface;
use IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface;
use IntegrityInvoice\Services\CompanyDetails\Updater as CompanyDetailsUpdater;
use Carbon\Carbon;

class SubscriptionController extends BaseController {
	
	public $tenant;
	public $tenantID;
	public $userId;
	public $accountPlan;
	public $accountPlans;	
	public $selected_plan;
	public $tenantVerification;	
	public $tenantStatus;
	public $validToDaysRemaining;
	public $validTo;
	public $validFrom;
	public $dateFormat;
	public $history;
	public $discount;
	public $coupon;
	public $paypalGateway;
	public $mailer;
	public $companyDetails;

	public function __construct(TenantRepositoryInterface $tenant, AccountPlanRepositoryInterface $accountPlan, PaymentsHistoryRepositoryInterface $history,
	DiscountRepositoryInterface $discount, CouponRepositoryInterface $coupon, PaypalPaymentGateway $paypalGateway, 
	CompanyDetailsRepositoryInterface $companyDetails, BillingMailer $mailer)
    {
    	$this->tenant = $tenant;
		$this->discount = $discount;
		$this->coupon = $coupon;
    	$this->accountPlan = $accountPlan;
		$this->accountPlans = $this->accountPlan->getAll();
		$this->history = $history;
    	$this->tenantID = Session::get('tenantID');
		$this->userId = Session::get('user_id');
		$this->plan_id = $this->tenant->find($this->tenantID)->account_plan_id;		 
		$this->tenantVerification = $this->tenant->verification($this->tenantID);
		$this->date_format = Preference::where('tenantID', '=', $this->tenantID)->pluck('date_format');		 
		$this->tenantStatus = $this->tenant->find($this->tenantID)->status;		 
		$this->validToDaysRemaining = $history->getValidToDaysRemaining($this->tenantID);
		$this->validTo = $history->validTo($this->tenantID);
		$this->validFrom = $history->validFrom($this->tenantID);
		$this->paypalGateway = $paypalGateway; 
		$this->companyDetails = $companyDetails;
		$this->mailer = $mailer;
    }
	
 
	public function index()
	{
		$account_type = $this->accountPlan->getAccountType($this->plan_id);
		
		if($this->validToDaysRemaining <= 0){
			$account_expired = 1;
		}
		else
		{
			$account_expired = 0;
		}

		$starter_plan = $this->accountPlan->findByType('Starter');
        $premium_plan  = $this->accountPlan->findByType('Premium');
        $super_premium_plan = $this->accountPlan->findByType('Super Premium');
		 
        return View::make('subscription.index')
       		 ->with(compact('account_type'))
        	 ->with(compact('account_expired'))
			 ->with(compact('starter_plan'))
			 ->with(compact('premium_plan'))
			 ->with(compact('super_premium_plan'));
	}

	 
	public function history()
	{
		$histories = $this->history->getAll($this->tenantID);
		$date_format = $this->date_format;
		$current_plan = $this->plan_id;
		
        return View::make('subscription.history')
		->with('title', 'Payment History')
		->with(compact('histories'))
		->with(compact('current_plan'))
		->with(compact('date_format'));
	}
	
	public function getPlan()
	{

		$plan = "";
		switch($this->plan_id){
			
			case 1:
			$plan = "Starter";
			break;
			
			case 2:
			$plan = "Premium";
			break;
			
			case 3:
			$plan = "Super Premium";
			break;

			default:
			$plan = "not set";
			break;
			
		}
		return $plan;
	} // ENd Get Plan 
	
	
	public function getNewPlan($planid){
		
		$plan = "";
		switch($planid){
			
			case 1:
			$plan = "Starter";
			break;
			
			case 2:
			$plan = "Premium";
			break;
			
			case 3:
			$plan = "Super Premium";
			break;

			default:
			$plan = "not set";
			break;
			
		}
		return $plan;
		
	}
	
	public function getPlanLabel($selected_plan)
	{

		$plan = "";
		switch($selected_plan){
			
			case 1:
			$plan = "Starter plan";
			break;
			
			case 2:
			$plan = "Premium plan";
			break;
			
			case 3:
			$plan = "Super Premium plan";
			break;

			default:
			$plan = "not set";
			break;
			
		}
		return $plan;
	}  
 
  
	public function cart(){
		
		if(Session::get('selected_plan'))
		{
			$selected_plan = Session::get('selected_plan');
			$plan = $this->getPlanLabel($selected_plan);
			$is_verification = 1;
		} 
		else
		{
			$selected_plan = Input::get('plan');
			$plan = $this->getPlanLabel($selected_plan);
			$is_verification = 0;
		}
 
		if($selected_plan == ""){
			Redirect::to('subscription')->with('failed_flash_message', 'No plan was selected');
		}
		
		
		// Verify Coupon code if it exists
		
		if(Input::get('coupon_code') && !is_null(Input::get('coupon_code')) && Input::get('coupon_code') != "")
		{
			$coupon_code = Input::get('coupon_code');
			
			// Check if valid at all		
			
			if($this->coupon->exists($coupon_code))
			{
				// Check if date is still valid
				if($this->coupon->valid($coupon_code))
				{
					// Check if Valid for selected plan
					if($this->coupon->validForSelectedPlan($coupon_code, $selected_plan))
					{
						// Set the Coupon discount
						$coupon_discount = $this->coupon->getCouponDiscount($coupon_code);
					}
					else
					{
						Redirect::to('subscription')->with('failed_flash_message', 'Coupon code is not valid for the selected plan.');
					}					
				}
				else
				{
					Redirect::to('subscription')->with('failed_flash_message', 'Coupon code has expired');
				}
				 
			}
			else
			{
				Redirect::to('subscription')->with('failed_flash_message', 'Invalid coupon code entered');
			}
		 
		}	
		 
		 
		// If downgrading and valid days is more than 0
		if($selected_plan < $this->plan_id && $this->validToDaysRemaining > 0)
		{  
			return Redirect::to('subscription')->with('failed_flash_message', 'Please wait for your current billing cycle to end before downgrading. Current billing will expire on '.AppHelper::date_to_text($this->validTo).', thank you.');
		}
		else if($selected_plan < $this->plan_id && $this->validToDaysRemaining < 1)
		{
			// Downgrade
			$this->tenant->update($this->tenantID, array('account_plan_id' => $selected_plan));
			
			// TO DO: Enter new history for downgrade - not sure, because once downgrading is done after current plan expires, 
			// it will apply constraint to make payment if not on FREE account
			
			return Redirect::to('subscription')->with('flash_message', 'Successfully downgraded to: '.AppHelper::get_subscription_plan($selected_plan));
			
		}
		
		// If Upgrading and account active on a plan and not FREE plan, get the current plan, plan price and prepare the discount to be removed off upgrading
		if($selected_plan > $this->plan_id && $this->validToDaysRemaining > 0){
			// Upgrading to a higher plan from an unexpired account
			$current_price_permonth = $this->accountPlan->getPlanPrice($this->plan_id);
			$current_price_perday = $current_price_permonth / 30;
			
			$upgrade_price_permonth = $this->accountPlan->getPlanPrice($selected_plan);
			$upgrade_price_perday = $upgrade_price_permonth / 30;
			
			// Get remaining amount to pay for the value of the remaining days on current account
			$upgrade_amount = (($upgrade_price_perday * $this->validToDaysRemaining) - ($current_price_perday * $this->validToDaysRemaining));
			
			$apply_upgrade_amount = true;
			$applied_upgrade_amount = sprintf("%01.2f", $upgrade_amount);
			$upgrading_from_unexpired_account = true;
			$extending_account = false;
			$renewing_expired_account = false;
			 
		}else if($selected_plan > $this->plan_id && $this->validToDaysRemaining <= 0){
			
			// Upgrading to a higher plan from an expired account
			
			$apply_upgrade_amount = false;
			$applied_upgrade_amount = 0;
			$upgrading_from_unexpired_account = true;
			$extending_account = false;
			$renewing_expired_account = false;			 
			
		}else if($selected_plan == $this->plan_id && $this->validToDaysRemaining <= 0){
			
			// Renewing expired account on same plan
			
			$apply_upgrade_amount = false;
			$applied_upgrade_amount = 0;
			$upgrading_from_unexpired_account = false;
			$extending_account = false;
			$renewing_expired_account = true;			 
			
		}else if($selected_plan == $this->plan_id && $this->validToDaysRemaining > 0){
			
			// Extending unexpired account on same plan
			$apply_upgrade_amount = false;
			$applied_upgrade_amount = 0;
			$upgrading_from_unexpired_account = false;
			$extending_account = true;
			$renewing_expired_account = false;
		}
		
		$last_recorded_start_date = $this->validFrom;
		$last_recorded_end_date = $this->validTo;
		
		/*
		if($this->account_plan != 1){
			// Valid from last valid to
			$record['last_recorded_start_date'] = $this->valid_from;
			$record['last_recorded_end_date'] = $this->valid_to;
		}else{
			// Valid from today
			$record['last_recorded_start_date'] = date('Y-m-d', strtotime('today'));
			$record['last_recorded_end_date'] = date('Y-m-d', strtotime('today'));
		}*/
		
		$plan_price = $this->accountPlan->getPlanPrice($selected_plan);
		
		$oneMonthDiscount = $this->discount->findByMonth('1')->value;
		$threeMonthsDiscount = $this->discount->findByMonth('3')->value;
		$sixMonthsDiscount = $this->discount->findByMonth('6')->value;
		$twelveMonthsDiscount = $this->discount->findByMonth('12')->value;
		
		$coupon_message = "";
		// Overide discount with coupon code if exists
		
		if(isset($coupon_discount) && $coupon_discount != "" & $coupon_discount != 0 && !is_null($coupon_discount))
		{
			$oneMonthDiscount = $coupon_discount;
			$threeMonthsDiscount = $coupon_discount;
			$sixMonthsDiscount = $coupon_discount;
			$twelveMonthsDiscount = $coupon_discount;
			
			Session::flash('flash_message', 'Coupon code: '.Input::get('coupon_code'). ' has been applied with a discount of '.$coupon_discount.'%');
 
		}
		 
		// Load Cart
		return View::make('subscription.cart')
				->with('tenantID', $this->tenantID)
				->with('current_plan', $this->getPlan())
				->with('scripts', 'cart')
				->with('title', 'Shopping Cart')				
				->with(compact('apply_upgrade_amount'))
				->with(compact('applied_upgrade_amount'))
				->with(compact('upgrading_from_unexpired_account'))
				->with(compact('extending_account'))
				->with(compact('renewing_expired_account'))
				->with(compact('last_recorded_start_date'))
				->with(compact('last_recorded_end_date'))
				->with(compact('plan'))
				->with(compact('is_verification'))
				->with(compact('plan_price'))
				->with(compact('oneMonthDiscount'))
				->with(compact('threeMonthsDiscount'))
				->with(compact('sixMonthsDiscount'))
				->with(compact('twelveMonthsDiscount'));
		 
	}


	public function card()
	{  
		return View::make('subscription.card')
		->with('title', 'Payment by card')
		->with('companyEmail', $this->companyDetails->find($this->tenantID)->email)
		->with('publishable_key', getenv('STRIPE_PUBLISHABLE_KEY'))
		->with('token_mount', AppHelper::encrypt(Input::get('amount'), $this->tenantID))		
		->with('item_number',  AppHelper::encrypt(Input::get('item_number'), $this->tenantID))
		->with('item_name',  AppHelper::encrypt(Input::get('item_name'), $this->tenantID))
		->with('apply_upgrade_amount',  AppHelper::encrypt(Input::get('apply_upgrade_amount'), $this->tenantID))
		->with('applied_upgrade_amount',  AppHelper::encrypt(Input::get('applied_upgrade_amount'), $this->tenantID))
		->with('thisplan',  AppHelper::encrypt(Input::get('thisplan'), $this->tenantID))
		->with('thisplan_price',  AppHelper::encrypt(Input::get('thisplan_price'), $this->tenantID))
		->with('amount_topay',  Input::get('amount'))		
		->with('subcr_duration',  AppHelper::encrypt(Input::get('subcr_duration'), $this->tenantID))
		->with('tenantID',  AppHelper::encrypt(Input::get('tenantID'), $this->tenantID))
		->with('last_recorded_start_date',  AppHelper::encrypt(Input::get('last_recorded_start_date'), $this->tenantID))
		->with('last_recorded_end_date',  AppHelper::encrypt(Input::get('last_recorded_end_date'), $this->tenantID))
		->with('upgrading_from_unexpired_account',  AppHelper::encrypt(Input::get('upgrading_from_unexpired_account'), $this->tenantID))
		->with('extending_account',  AppHelper::encrypt(Input::get('extending_account'), $this->tenantID))
		->with('renewing_expired_account',  AppHelper::encrypt(Input::get('renewing_expired_account'), $this->tenantID))
		->with('subcr_plan',  AppHelper::encrypt(Input::get('subcr_plan'), $this->tenantID));
	}
	
	
	public function process_card()
	{
		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
			
		$billing = App::make('IntegrityInvoice\Billing\BillingInterface');
		
		try
		{
			$customerID = $billing->charge(array(
				'email' => Input::get('email'),
				'token' => Input::get('stripe-token'),
				'amount' => AppHelper::decrypt(Input::get('token_mount'), $this->tenantID) * 100,			
				'item_name' => AppHelper::decrypt(Input::get('item_name'), $this->tenantID),
				'item_number' => AppHelper::decrypt(Input::get('item_number'), $this->tenantID),
				'apply_upgrade_amount' => AppHelper::decrypt(Input::get('apply_upgrade_amount'), $this->tenantID),
				'applied_upgrade_amount' => AppHelper::decrypt(Input::get('applied_upgrade_amount'), $this->tenantID),
				'thisplan' => AppHelper::decrypt(Input::get('thisplan'), $this->tenantID),
				'thisplan_price' => AppHelper::decrypt(Input::get('thisplan_price'), $this->tenantID) * 100,
				'subcr_duration' => AppHelper::decrypt(Input::get('subcr_duration'), $this->tenantID),
				'tenantID' => AppHelper::decrypt(Input::get('tenantID'), $this->tenantID),
				'last_recorded_start_date' => AppHelper::decrypt(Input::get('last_recorded_start_date'), $this->tenantID),
				'last_recorded_end_date' => AppHelper::decrypt(Input::get('last_recorded_end_date'), $this->tenantID),
				'upgrading_from_unexpired_account' => AppHelper::decrypt(Input::get('upgrading_from_unexpired_account'), $this->tenantID),
				'extending_account' => AppHelper::decrypt(Input::get('extending_account'), $this->tenantID),
				'renewing_expired_account' => AppHelper::decrypt(Input::get('renewing_expired_account'), $this->tenantID),
				'subcr_plan' => AppHelper::decrypt(Input::get('subcr_plan'), $this->tenantID)
			));
			
			// Save customer ID to database	
			$companyDetailsUpdater = new CompanyDetailsUpdater($this->companyDetails, $this);
			$companyDetailsUpdater->updateBillingId(array(
										  	'tenantID' => $this->tenantID,
										  	'cardBillingId' => $customerID
										  ));
		}
		catch(exception $e)
		{
			return Redirect::refresh()->with('failed_flash_message', $e->getMessage())->withInput();
		}
		
		$subscription = $this->getPlan($this->plan_id);
		 
		//Send Email.
		$tenant = $this->tenant->find($this->tenantID);
		$last_history = $this->history->findFirst($this->tenantID);		
		$newplan = $this->getNewPlan($tenant->account_plan_id);
		
		$user = User::where('tenantID', '=', $this->tenantID)->where('level', '=', 2)->first();
	 
		$date_paid = AppHelper::date_to_text(substr($last_history->created_at, 0, 10), $preferences->date_format);
		
		$this->mailer->subscription_payment($user->firstname, $last_history->amount, $last_history->payment_system, Input::get('email'), $date_paid, 'Subscription payment was successfully processed.'); 
					
	     return Redirect::to('subscription/card_success');
		
	}
	
	 
	
	public function paypal(){
		 
 	  // There should be no output at this point.  To process the POST data,
      // the submit_paypal_post() function will output all the HTML tags which
      // contains a FORM which is submited instantaneously using the BODY onload
      // attribute.  In other words, don't echo or printf anything when you're
      // going to be calling the submit_paypal_post() function.
 
      // This is where you would have your form validation  and all that jazz.
      // You would take your POST vars and load them into the class like below,
      // only using the POST values instead of constant string expressions.
 
      // For example, after ensuring all the POST variables from your custom
      // order form are valid, you might have:
      //
      //$p->add_field('first_name', $_POST['first_name']);
      //$p->add_field('last_name', $_POST['last_name']);
  
      //$p->dump_fields();      // for debugging, output a table of all the fields

        // $this->paypalGateway->add_field('business', 'demoseller@integritywebapp.com'); // TEST
        // $this->paypalGateway->add_field('business', 'sales@integrityinvoice.com'); // LIVE
        $this->paypalGateway->add_field('business', Config::get('paypal.seller_email'));
		$this->paypalGateway->add_field('amount', Input::get('amount'));
		$this->paypalGateway->add_field('item_number',Input::get('item_number'));
		$this->paypalGateway->add_field('item_name', Input::get('item_name'));
		
		// In this Order TenantID & Renewing_expired_account & Extending_account & Upgrading_from_unexpired_account & Last_recorded_start_date & Last_recorded_end_date
		$this->paypalGateway->add_field('custom',  trim(Input::get('tenantID')).'&'.trim(Input::get('renewing_expired_account')).'&'.trim(Input::get('extending_account')).'&'.trim(Input::get('upgrading_from_unexpired_account')).'&'.trim(Input::get('last_recorded_start_date')).'&'.trim(Input::get('last_recorded_end_date')));
	    $this->paypalGateway->add_field('return', URL::route('subscription_successful'));
	    $this->paypalGateway->add_field('cancel_return', URL::route('subscription_cancel'));
	    $this->paypalGateway->add_field('notify_url', URL::route('subscription_ipn')); // <-- IPN url 
	    $this->paypalGateway->add_field('no_shipping', 1);
	    $this->paypalGateway->add_field('no_note', 1);
	    $this->paypalGateway->add_field('currency_code', 'USD');
				
		// otherwise, don't write anything or (if you want to 
		// change the default button text), write this:
		// $this->paypalGateway->button('Click to Pay!');
		
	    $this->paypalGateway->submit_paypal_post();	
		
		// $this->view('paypal/form', $data);	
	}



	public function card_success()
	{
		// New subscription		
		$tenant = $this->tenant->find($this->tenantID);
		$last_history = $this->history->findFirst($this->tenantID);		
		$newplan = $this->getNewPlan($tenant->account_plan_id);
		  
	    return View::make('subscription/card_success')->with(compact('newplan'))
		->with(compact('last_history'))
	    ->with('flash_message', 'Payment was successful');
	}

	
	

	public function cancel()
	{
		return View::make('subscription/cancel')
		->with('title', 'Payment cancelled');
	}
	
	public function success()
	{
		// This is where you would probably want to thank the user for their order
		// or what have you.  The order information at this point is in POST 
		// variables.  However, you don't want to "process" the order until you
		// get validation from the IPN.  That's where you would have the code to
		// email an admin, update the database with payment status, activate a
		// membership, etc.
	
		// You could also simply re-direct them to another page, or your own 
		// order status page which presents the user with the status of their
		// order based on a database (which can be modified with the IPN code 
		// below).
		
		// Clear out incase selected plan was still selected.
		
		if(Session::get('selected_plan'))
		{
			// $array_items = array('selected_plan' => '');
			Session::forget('selected_plan');
		}
		
		return Redirect::route('subscription_history')
					->with('flash_message', 'Paypal payment was successful. See details below.');
		 
		//$data['pp_info'] = $this->input->post();
		/* $subscription = $this->getPlan();
		$pp_info = Input::all(); 
		
		return View::make('subscription/success')
		->with('title', 'Payment was successful')
		->with(compact('subscription'))
		->with(compact('pp_info')); */
	}
	
}