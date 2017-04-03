<?php namespace IntegrityInvoice\Billing;

use Config;
use Stripe;
use Stripe_Charge;
use Stripe_Customer;
use Stripe_InvalidRequestError;
use Stripe_CardError;
use Stripe_AuthenticationError;
use Stripe_ApiConnectionError;
use Stripe_Error;
use Exception;

use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Services\PaymentHistory\Creator as PaymentHistoryCreator;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Services\Tenant\Updater as TenantUpdater;

// Stripe_Customer::retrieve($companyDetails->billingId);

class StripeBilling implements BillingInterface{
	
	public $paymentHistory;
	public $tenant;
	public $tenantID;
	
	public function __construct(PaymentsHistoryRepositoryInterface $paymentHistory, TenantRepositoryInterface $tenant)
	{
		$this->paymentHistory = $paymentHistory;
		$this->tenant = $tenant;		
		Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
	}
	
	
	public function charge(array $data)
	{
	 
		try
		{
			$customer = Stripe_Customer::create(array(			
				'description' => $data['email'],
				'card' => $data['token']
			));
				
		
		   Stripe_Charge::create(array(
				'customer' => $customer->id,
				'amount' => $data['amount'], // £10
				'currency' => 'gbp'			
			));
			
			// Update Subscription
			return $this->validateCharge($data, $customer->id);
			
			// return $customer->id;
		} 
		
		catch(Stripe_InvalidRequestError $e)
		{
			// Invalid parameters were supplied to Stripe's API
			throw new Exception($e->getMessage());
		}
		
		catch (Stripe_CardError $e)
		{
			throw new Exception($e->getMessage());
		}
		
		catch(Stripe_AuthenticationError $e)
		{
			// Authentication with Stripe API failed
			// (Maybe you changed API keys recently)
			throw new Exception($e->getMessage());
		}
		
		catch(Stripe_ApiConnectionError $e)
		{
			// Network Communication with Stripe failed
			throw new Exception($e->getMessage());
		}
		
		catch(Stripe_Error $e)
		{
			// Display a very generic error to the user
			throw new Exception($e->getMessage());
		}
		
		catch(Exception $e)
		{
			// Something else happened, completely unrelated to Stripe
			throw new Exception($e->getMessage());
		}
	}


	public function get_valid_duration_from_item_number($item_number){
		
		switch((int)$item_number){
		
	 		 case 1:
				 $data = array('plan' => 2, 'duration' => 30);
			 break;
			 
			 case 2:
				  $data = array('plan' => 2, 'duration' => 91);
			 break;
			 
			 case 3:
				  $data = array('plan' => 2, 'duration' => 183);
			 break;
			 
			 case 4:
				  $data = array('plan' => 2, 'duration' => 365);
			 break;
			 
			 case 5:
				  $data = array('plan' => 3, 'duration' => 30);
			 break;
			 
			 case 6:
			  	  $data = array('plan' => 3, 'duration' => 91);
			 break;
			 
			 case 7:
				  $data = array('plan' => 3, 'duration' => 183);
			 break;
			 
			 case 8:
				  $data = array('plan' => 3, 'duration' => 365);
			 break;
			 
			 case 9:
				  $data = array('plan' => 4, 'duration' => 30);
			 break;
			 
			 case 10:
				  $data = array('plan' => 4, 'duration' => 91);
			 break;
			 
			 case 11:
				  $data = array('plan' => 4, 'duration' => 183);
			 break;
			 
			 case 12:
				  $data = array('plan' => 4, 'duration' => 365);
			 break;
			 
			 default:
			 	 $data = array('plan' => 0, 'duration' => 0);
			 break;
		 }
		 
		 return $data;
			 	
	} // End get_valid_duration_from_item_number
	
	
	 
	public function validateCharge($data, $customerId)
	{
	  
			// mail('sales@integrityinvoice.com', 'got to verified point', 'Oh yes got there!');
			
			 $item_number = $data['item_number'];
			 $item_name = $data['item_name'];
			 //$payment_status = $data['payment_status'];
			 $payment_amount = $data['amount'];
			 $payment_currency = 'gbp';
			 $txn_id = $data['token'];
			 $receiver_email = 'sales@sighted.com';
			 $payer_email = $data['email'];
			 
			 
			 // CUSTOM VARIABLES
			// list($tenantID, $renewing_expired_account, $extending_account, $upgrading_from_unexpired_account, $last_recorded_start_date, $last_recorded_end_date ) = explode('&', $custom);
			 
			 // Trim any White space
			 $this->tenantID = $data['tenantID'];
			 $renewing_expired_account = $data['renewing_expired_account'];
			 $extending_account = $data['extending_account'];
			 $upgrading_from_unexpired_account = $data['upgrading_from_unexpired_account'];
			 $last_recorded_start_date = $data['last_recorded_start_date'];
			 $last_recorded_end_date = $data['last_recorded_end_date'];
			 
			 $message = $this->tenantID. " , ". $renewing_expired_account. " , ". $extending_account . " , ". $upgrading_from_unexpired_account. " , ". $last_recorded_start_date . " , ". $last_recorded_end_date;
			 
			
			 // RECORD PROCESSING LOGIC
			 
			 // Renewing expired account logic
			 if($renewing_expired_account == "yes"){
			 	$valid_from = date('Y-m-d', strtotime('today'));
				$v = $this->get_valid_duration_from_item_number($item_number);
				$duration = $v['duration'];
				$plan = $v['plan']; 
				
				$new_date = strtotime ( '+'.(int)$duration.' day' , strtotime ($valid_from));		
				$valid_to = date ( 'Y-m-d' , $new_date);
			 }
			 
			 // Extending current account logic
			 if($extending_account == "yes"){
			 	
			 	$valid_from = $last_recorded_end_date;
				$v = $this->get_valid_duration_from_item_number($item_number);				
				$duration = $v['duration'];				
				$plan = $v['plan'];
				// Find the difference of today and last valid to date on the account
				$diff = strtotime($last_recorded_end_date) - strtotime($valid_from);
				$days = floor($diff / (60*60*24));
				// Add difference of days still active to the number of days newly paid for
			 	$total_duration = $duration + $days;
				
				$new_date = strtotime ( '+'.(int)$total_duration.' day' , strtotime ($valid_from));		
				$valid_to = date ( 'Y-m-d' , $new_date);
			 }
			 
			 
			 // Upgrading from unexpired account logic
			 if($upgrading_from_unexpired_account == "yes"){
			 	
				$valid_from = date('Y-m-d', strtotime('today'));
				$v = $this->get_valid_duration_from_item_number($item_number);				
				$duration = $v['duration'];				
				$plan = $v['plan'];
				
				// Find the difference of today and last valid to date on the account
				$diff = strtotime($last_recorded_end_date) - strtotime($valid_from);
				$days = floor($diff / (60*60*24));
				
				// Add difference of days still active to the number of days newly paid for
			 	$total_duration = $duration + $days;
				
				$new_date = strtotime ( '+'.(int)$total_duration.' day' , strtotime ($valid_from));		
				$valid_to = date ('Y-m-d' , $new_date);
			 }
 
		 	// Add Transaction
			//$today = strftime("%Y-%m-%d", time());
		
			// set new expiry date
			//$new_exp = strtotime ( '+'.(int)$new_plan_duration.' day' , strtotime ($today));
			//$new_exp = date ( 'Y-m-j' , $new_exp);	
			
			$paymentHistoryCreatorService = new PaymentHistoryCreator($this->paymentHistory, $this);		
			if($paymentHistoryCreatorService->createByCard(array(
				'txn_id' => $txn_id,
				'cardBillingId' => $customerId,
				'sender_email' => $payer_email,
				'tenantID' => $this->tenantID,
				'amount' => $payment_amount / 100,
				'valid_from' => $valid_from,
				'valid_to' => $valid_to,
				'subscription_type' => $plan,
				'payment_system' => 'Credit / Debit Card',
				'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
				'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())		 
			)))
			{
				// Log Valid IPN transaction.
				//$this->log_ipn_results(true);				
				
				// upgrade
				$tenantUpdateService = new TenantUpdater($this->tenant, $this);
				$tenantUpdateService->updateStatus(array(
					'account_plan_id' => $plan,
					'status' => 1,
					'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())		 
				));
			  
			}
		 
			return $customerId;		 
	  
	} // END validate_ipn
	
 
}
