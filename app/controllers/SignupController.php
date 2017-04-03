<?php
use IntegrityInvoice\Utilities\MessagesHelper;
use IntegrityInvoice\Utilities\AppHelper;
use IntegrityInvoice\Repositories\TenantRepositoryInterface;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentGatewaysRepositoryInterface;
use IntegrityInvoice\Services\PaymentGateway\Creator as PaymentGatewayCreator;

use IntegrityInvoice\Handlers\UserEventHandler;
use Carbon\Carbon;

class SignupController extends BaseController {

	 private $newTenantID;
	 private $activationKey;
	 private $activationUrl;
	 private $user;
	 private $tenant;
	 private $preference;
     private $paymentgateway;

	 public function __construct(TenantRepositoryInterface $tenant, UserRepositoryInterface $user, PaymentGatewaysRepositoryInterface $paymentgateway, PreferenceRepositoryInterface $preference)
	 {
	 	$this->user = $user;
		$this->tenant = $tenant;
		$this->preference = $preference;
         $this->paymentgateway = $paymentgateway;
	 }
	 
	
	public function create()
	{
        return View::make('signup.signup')->with('title', 'Signup form');
	}

	
	public function store()
	{	
		// Auto create new TenantID
		$this->newTenantID = $this->new_tenantID();
			
		// 1. New activation key
		$this->activationKey =  mt_rand() . mt_rand() . mt_rand() . mt_rand() . mt_rand();
		
		$tenantCreator = new IntegrityInvoice\Services\Tenant\Creator($this->tenant, $this);
		
		// Check if new tenant signed up with a referral code		
		if(Input::get('referral_code') != "" && Input::get('referral_code') != NULL){
			// see if code exists
			if(!$referral_tenant = $tenantCreator->checkReferralCodeExists(trim(Input::get('referral_code')))){
				return $this->invalidReferralCode();
			}
			
		}

		// Setup this user's refeeral code
		$affilate_code = AppHelper::alphaID(ceil(preg_replace('/[^0-9,]|,[0-9]*$/','', $this->newTenantID ) / (1754*89200)));
		$referral_code = AppHelper::alphaID(ceil(preg_replace('/[^0-9,]|,[0-9]*$/','', $this->newTenantID ) / (2754*98211)));
	 	
	 		
		// Event::fire('signup.create');		
		return $tenantCreator->create(array(
					'tenantID' => $this->newTenantID,
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
					'account_plan_id' => 1,
					'referrer' =>  Input::get('referrer'),
					'affiliate_code' => $affilate_code,
					'referral_code' => $referral_code,
					'activation_key' => $this->activationKey,					 
					'email' => Input::get('email'),
					'password' => Input::get('password')				 
				));
 
	}
	
	
	public function tenantCreationSucceeds()
	{
		// 2. Create new business details
		$companyDetailsCreator = new IntegrityInvoice\Services\CompanyDetails\Creator($this);
		
		return $companyDetailsCreator->create(array(
					'email' => Input::get('email'),
					'created_at' => Carbon::now(),
					'updated_at' => Carbon::now(),
					'tenantID' => $this->newTenantID
				));
	 
	}


	public function companyDetailsCreationSucceeds()
	{
		// 3. Create default business preferences
		$preferenceCreator = new IntegrityInvoice\Services\Preference\Creator($this);
		return $preferenceCreator->create(array(

			'tenantID' => $this->newTenantID,
			'date_format' => 'dd/mm/yyyy',
			'time_zone' => 'Europe/London',
			'page_record_number' => 10,
			'invoice_template' => 2,
			'vat_id' => '',
			'payment_details' => '',
			'invoice_prefix' => '',
			'tax_1name' => 'VAT',
			'tax_2name' => 'Tax 2',
			'footnote1' => 'Thank you',
			'footnote1' => 'We appreciate your business',
			'invoice_note' => MessagesHelper::invoice_note(),
			'quote_note' => MessagesHelper::quote_note(),
			'reminder_message_subject' => MessagesHelper::reminder_message_subject(),
			'reminder_message' => MessagesHelper::reminder_message(),
			'progress_payment_message_subject' => MessagesHelper::progress_payment_message_subject(),
			'progress_payment_message' => MessagesHelper::progress_payment_message(),
			'invoice_send_message_subject' => MessagesHelper::invoice_send_message_subject(),
			'invoice_send_message' => MessagesHelper::invoice_send_message(),
			'quote_send_message_subject' => MessagesHelper::quote_send_message_subject(),
			'quote_send_message' => MessagesHelper::quote_send_message(),
			'thank_you_message_subject' => MessagesHelper::thank_you_message_subject(),
			'thank_you_message' => MessagesHelper::thank_you_message(),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()

		));
 
	}

	public function preferenceCreationSucceeds(){
	 
		// Default Preferences successfully inserted,
		// 4. Now create payment gateway		
		$paymentgatewayCreatorService = new PaymentGatewayCreator($this->paymentgateway, $this);	
	    return $paymentgatewayCreatorService->create(array(
				'tenantID' => $this->newTenantID,
				'paypal_email' => '',
				'stripe_secret_key' => '',
				'stripe_publishable_key' => '',		 
				'created_at' => Carbon::now(),
				'updated_at' => Carbon::now()
		 )); 	
		 
	}
	
	
	public function paymentGatewayCreationSucceeds()
	{
		// Payment gateway creation succeeded
		// Create User		
		$userCreator = new IntegrityInvoice\Services\User\Creator($this->user, $this);
		return $userCreator->create(array(
			'tenantID' => $this->newTenantID,
			'email' => Input::get('email'),
			'password' => Hash::make(Input::get('password')),
			'level' => 2,
			'theme_id' => 6,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
			'firsttimer' => 1			
	 	), null, TRUE);
		 
	}
	
	
	public function userCreationSucceeds($newUser)
	{
		 
		// User creation was successful,

		// 6. Send the Welcome Email
		$email = $newUser->email; 
		//$firstname = $newUser->firstname;
		$selected_plan = Input::get('selected_plan');
		$activationKey = "";
		
		// welcome email
		$this->activationUrl = Config::get('app.app_domain').'signup/verify/'.$this->activationKey.'/'.$selected_plan;

		// 7. Log the new User in and redirect them to the welcome page
		if(Auth::user()->attempt(array( 'email' => $email, 'password' => Input::get('password'))))
		{
			$user = Auth::user()->get();

		   //  Set session and redirect to dashboard		 
			Session::put('user_id', $user->id);
			Session::put('email', $user->email);
			Session::put('tenantID', $user->tenantID);
			Session::put('firstname', $user->firstname);
			Session::put('lastname', $user->lastname);
			Session::put('theme_id', $user->theme_id);
			Session::put('invoice_template', Preference::where('tenantID', '=', $user->tenantID)->pluck('invoice_template'));
			Session::put('account_plan', Tenant::where('tenantID', '=', $user->tenantID)->pluck('account_plan_id'));
			Session::put('user_level', $user->level);
			Session::put('is_logged_in', true);

			// 7.2 Process referral code 
			$tenantCreator = new IntegrityInvoice\Services\Tenant\Creator($this->tenant, $this);

           		 $referral_tenant = NULL;
			if(Input::get('referral_code') != "" && Input::get('referral_code') != NULL)
			{
              			$referral_tenant = $tenantCreator->checkReferralCodeExists(trim(Input::get('referral_code')));
            		}
		    	
		    	//  8.Signup event
		    	
		    	
	           	Event::fire('user.signup', ['user' => $user, 'activationCode' => $this->activationUrl, 'referral_tenant' => $referral_tenant]);
			//Event::fire('user.login', ['user' => $user]);
            		
			return Redirect::to('settings/onetime')->with('flash_message', 'You account is almost ready to use, please set the following.');
			
		}
		else
		{
			return Redirect::to('login')->with('login_errors', true);
		}
	 
	}

	
	public function paymentGatewayCreationFails()
	{
		return Redirect::route('signup')->with('failed_flash_message', 'A technical problem occured, please try again later')->withInput();
	}
	
	public function tenantCreationFails($errors){
			
		return Redirect::route('signup')->withErrors($errors)->withInput();
	}
	
	public function invalidReferralCode(){
		return Redirect::route('signup')->with('failed_flash_message', 'Referral code is invalid.')->withInput();
	}
	
	public function companyDetailsCreationFails($errors){
		
		return false;
	}
	
	public function preferenceCreationFails($errors){
		
		return false;
	}
	
	public function userCreationFails($errors){
			
		return Redirect::route('signup')->withErrors($errors)->withInput();
	}


	// Auto Tenant ID generator
	public function new_tenantID(){
		
		$last_tenant_id = (int)$this->last_tenant_id();
		
		if(!$last_tenant_id || $last_tenant_id == 0 || $last_tenant_id == "")
		{
			$last_id = 1;
		}
		else
		{			
			$last_id = $last_tenant_id['id'];	
			$last_id += 1;	
		}
	
		$str = (string)$last_id;	
		
		while(strlen($str) < 7)
		{
			$str = '0'.	$str;
		}
		
		$time = time();
		$ran1 = rand(0,9);
		$ran2 = rand(11,90);
		
		$newIdString = 'bl'.$ran1.$str.$time.$ran2;
	
		return $newIdString;
		
	}
	
	
	// Get the last id of the table rows
	public function last_tenant_id(){
 
		$last_id = (int)DB::table('tenants')->orderBy('id', 'desc')->pluck('id');	
	 
		if($last_id <= 0 || is_null($last_id))
		{
			return 0;
		}
		else
		{
			return $last_id;
		}
		
	}
	
  
	// Verify email at signup
	public function verify($verifystring, $plan){
		
		$verifier = new IntegrityInvoice\Services\Tenant\Verifier($this);

		return $verifier->verify(array(
			'verifystring' => $verifystring,
			'plan' => $plan,
		));
		
	}
	
	public function tenantVerificationSucceeds()
	{
		Auth::user()->logout();
		return Redirect::route('login')->with('flash_message', 'Thank you for verifying your email. Please login below.');
	}
	
	public function tenantAlreadyVerified()
	{
		return "Your email has already been verified. To login". HTML::linkRoute('login', ' click here',  array(), array('class' => ''));
	}
	
	public function tenantVerificationFails($errors)
	{
		return Redirect::route('login')->withErrors($errors);
	}	
	
	public function tenantVerificationSucceedsToUpgrade()
	{
		// Send to subscription upgrade page
		return true;
	}
	
	
	public function canceled()
	{
		return View::make('signup.cancel')->with('title', 'Sorry to see you go');
	}

}
