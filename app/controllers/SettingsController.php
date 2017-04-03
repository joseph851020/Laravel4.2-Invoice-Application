<?php
use IntegrityInvoice\Utilities\AppHelper;
use IntegrityInvoice\Mailers\SignupMailer;
use IntegrityInvoice\Repositories\PaymentGatewaysRepositoryInterface;
use IntegrityInvoice\Repositories\UserRepositoryInterface;
use IntegrityInvoice\Repositories\CompanyDetailsRepositoryInterface;
use IntegrityInvoice\Services\PaymentGateway\Reader as PaymentGatewayReader;
use IntegrityInvoice\Services\PaymentGateway\Updater as PaymentGatewayUpdater;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Preference\Updater;
use IntegrityInvoice\Services\Preference\OnetimeUpdater;
use IntegrityInvoice\Services\CompanyDetails\CompanyOntimeUpdater;
use IntegrityInvoice\Services\User\FullNameUpdater;
use Carbon\Carbon;
 
class SettingsController extends BaseController {
 	
	public $tenantID;
 	public $preference;
	public $userId;
	public $tenantVerification;
	public $paymentgateway;
	public $company;
	public $user;
	private $mailer;

	public function __construct(PreferenceRepositoryInterface $preference, PaymentGatewaysRepositoryInterface $paymentgateway, SignupMailer $mailer, UserRepositoryInterface $user, CompanyDetailsRepositoryInterface $company)
    {
    	$this->preference = $preference;
		$this->company = $company;
		$this->paymentgateway = $paymentgateway;
    	$this->tenantID = Session::get('tenantID');
		$this->userId = Session::get('user_id');
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
		$this->user = $user;
		$this->mailer = $mailer;
    }
	
	public function index()
	{
		if(Session::has('change_of_currency'))
		 {
			Session::forget('change_of_currency');
		 }
		
		 return View::make('settings.index')
			   ->with('title', 'Account Settings')
			   ->with('currencies', Currency::All())
			   ->with('preferences', Preference::where('tenantID', '=', Session::get('tenantID'))->first());
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		// If New default currency exists on tenant profile, delete it.
		$cur = Input::get('currency');
				
		if(CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code', '=', $cur)->first()){			
			CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code', '=', $cur)->delete();		 
		}
		
		if(Preference::where('tenantID', '=', Session::get('tenantID'))->pluck('currency_code') != $cur)
		{
			$currency_change_message = "We noticed that you have changed your home currency. Please update your currency exchange rates if you bill in multiple currencies. See <a class='ordinary_link' href=".URL::route('currency_rates').">currency exchange </a> under settings tab in the navigation menu.";
			Session::put('change_of_currency', $currency_change_message);
		}
		
		
		$updateService = new Updater($this->preference, $this);
	
		return $updateService->update(array(
			'vat_id' =>Input::get('vat_id'),
			'company_reg' =>Input::get('company_reg'),
			'tax_perc1' =>Input::get('tax_perc1'),
			'tax_perc2' =>Input::get('tax_perc2'),
			'tax_1name' =>Input::get('tax_1name'),
			'tax_2name' =>Input::get('tax_2name'),
			'currency_code' => Input::get('currency'),
			'invoice_prefix' =>Input::get('invoice_prefix'),			
			'date_format' =>Input::get('date_format'),
			'time_zone' => Input::get('time_zone'),
			'industry' => Input::get('industry'),
			'business_model' =>Input::get('business_model'),
			'bill_option' =>Input::get('bill_option'),
			'enable_discount' =>Input::get('enable_discount'),
			'enable_tax' =>Input::get('enable_tax'),
			'page_record_number' =>Input::get('page_record_number'),
			'footnote1' =>Input::get('footnote1'),
			'footnote2' =>Input::get('footnote2'),
			'payment_details'=> Input::get('payment_details'),
			'invoice_send_message_subject' =>Input::get('invoice_send_message_subject'),
			'invoice_send_message' =>Input::get('invoice_send_message'),
			'quote_send_message_subject' =>Input::get('quote_send_message_subject'),
			'quote_send_message' =>Input::get('quote_send_message'),
			'invoice_note' =>Input::get('invoice_note'),
			'quote_note' =>Input::get('quote_note'),
			'reminder_message_subject' =>Input::get('reminder_message_subject'),
			'reminder_message' =>Input::get('reminder_message'),
			'progress_payment_message_subject' =>Input::get('progress_payment_message_subject'),
			'progress_payment_message' =>Input::get('progress_payment_message'),
			'thank_you_message_subject' =>Input::get('thank_you_message_subject'),
			'thank_you_message' =>Input::get('thank_you_message'),
			'updated_at' => Carbon::now()
		));
 		
	}


	public function preferenceUpdateFails($errors){
		
		return Redirect::route('settings')->withErrors($errors)->withInput();
	}
	
	public function preferenceUpdateSucceeds(){
	 
		return Redirect::route('settings')
					->with('flash_message', 'Update was successful. ' . (Session::has('change_of_currency') ? Session::get('change_of_currency') : ""));
	}
 

	public function invoice_template()
	{ 
		 return View::make('settings.invoice_template')
			   ->with('title', 'Invoice Template')
			   ->with('preferences', Preference::where('tenantID', '=', Session::get('tenantID'))->first());
	}


	public function apply_invoice_template($id){
		
		$updateService = new Updater($this->preference, $this);	
		$updateService->update_template(array(			 
			'invoice_template' => $id,			 
			'updated_at' => Carbon::now()
		));
		
		return Redirect::route('invoice_template')->with('flash_message', 'Invoice template activated');
	}
	 
	
	public function paymentgateways()
	{
		 
		$readerService = new PaymentGatewayReader($this->paymentgateway, $this);
		$paymentgateways = $readerService->read();
		
		
		if($paymentgateways->stripe_secret_key == "" || $paymentgateways->stripe_secret_key == null)
		{
			$secret_key = $paymentgateways->stripe_secret_key;
		}
		else
		{
			$secret_key = AppHelper::decrypt($paymentgateways->stripe_secret_key, $this->tenantID);
		}
		
		if($paymentgateways->stripe_publishable_key == "" || $paymentgateways->stripe_publishable_key == null)
		{
			$publishable_key = $paymentgateways->stripe_publishable_key;
		}
		else
		{
			$publishable_key = AppHelper::decrypt($paymentgateways->stripe_publishable_key, $this->tenantID);
		}
 
        return View::make('settings.paymentgateways')
		   ->with('title', 'Payment gateways')
		   ->with('paypal_email', $paymentgateways->paypal_email)
		   ->with('secret_key', $secret_key)
		   ->with('publishable_key', $publishable_key);
    }    
	
	
	public function store_paymentgateway()
	{		 
		$updateService = new PaymentGatewayUpdater($this->paymentgateway, $this);	
		return $updateService->update(array(
			'paypal_email' =>Input::get('paypal_email'),
			'stripe_secret_key' =>Input::get('sct_key'),
			'stripe_publishable_key' =>Input::get('pub_key'),
			'updated_at' => Carbon::now()
		)); 
		 
    }
	
  
	public function paymentGatewayUpdateFails($errors){
		
		return Redirect::route('paymentgateways')->withErrors($errors)->withInput();
	}
	
	public function paymentGatewayUpdateSucceeds(){
		
		return Redirect::route('paymentgateways')
					->with('flash_message', 'Update was successful');
	}
	
	public function invoice_update_settings()
	{
		if(Input::get('enable_discount')){
			$enable_discount = Input::get('enable_discount');
		}
		else{
			$enable_discount = 0;
		}
		
		if(Input::get('enable_tax')){
			$enable_tax = Input::get('enable_tax');
		}
		else{
			$enable_tax = 0;
		}
	 
		
		if(trim(Input::get('form_type')) == "" || trim(Input::get('form_type')) == NULL){
			$form_type = "invoice";
		}else{
			$form_type = trim(Input::get('form_type'));
		}
		
		
		if(trim(Input::get('form_type')) != "invoice" && trim(Input::get('form_type')) != "quote" && trim(Input::get('form_type')) != "credit"){
			$form_type = "invoice";
		}
		
		
		$updateService = new Updater($this->preference, $this);
	    return $updateService->invoice_settings(array(			
			'business_model' => (int)Input::get('business_model'),
			'bill_option' => (int)Input::get('bill_option'),
			'enable_discount' => (int)$enable_discount,
			'enable_tax' => (int)$enable_tax,								 
			'updated_at' => Carbon::now()
		), $form_type);
		 
	}
	
	public function invoiceSettingsSucceeds($type = 'invoice')
	{
		if($type == 'invoice'){
			return Redirect::route('create_invoice');
		}else if($type == 'quote'){
			return Redirect::route('create_quote');
		}		
	}
	
	
	public function onetime()
	{
		return View::make('settings.onetime')
		   ->with('title', 'Onetime settings');
	}
	
	public function onetime_update()
	{
		$updateService = new OnetimeUpdater($this->preference, $this);	
		 
	    $updateService->update_company_details(array(	    						 
			'updated_at' => Carbon::now()
		));
		
		// Update company
		$companyUpdateService = new CompanyOntimeUpdater($this->company, $this);
		$companyUpdateService->update(array(			 
			'company_name' => Input::get('company_name'),		 
			'country' => Input::get('countries'),			  					 
			'updated_at' => Carbon::now()
		));
		
		$user = Auth::user()->get();
		
		$theme_id = Input::get('theme_id');
		
		if($theme_id == 0){
			$theme_id == 6;
		}
		
		// Udate User fullname
		$fullnameUpdateService = new FullNameUpdater($this->user, $this);
		$fullnameUpdateService->update($user->id, array(			 
			'firstname' => Input::get('firstname'),	
			'lastname' => Input::get('lastname'),
			'theme_id' => $theme_id,				 
			'updated_at' => Carbon::now()
		));	
		
		// Update Session
		
		Session::put('firstname', Input::get('firstname'));
		Session::put('lastname', Input::get('firstname'));
		Session::put('theme_id', Input::get('theme_id'));
		Session::flash('thank_you_for_signing_up','thank you for signing up.');
		
		if($user->firstname == NULL && $user->level > 1){
			$this->mailer->signup_notification(Input::get('firstname'), $user->email);	
		}

		// How did you find out about us
		if(Input::get('found_integrity') != ""){
			$this->mailer->signup_found_via(Input::get('found_integrity'));	
		}

		return $updateService->update(array(			 
			'currency_code' =>Input::get('currency'),
			'date_format' =>Input::get('date_format'),
			// 'time_zone' => Input::get('time_zone'),
			'business_model' =>Input::get('business_model'),
			'bill_option' => Input::get('bill_option'),
			'industry' => Input::get('industry'),					 
			'updated_at' => Carbon::now()
		));
	}
	
	public function onetimeUpdateFails($errors){
		
		return Redirect::route('onetime')->withErrors($errors)->withInput();
	}
	
	public function onetimeUpdateSucceeds(){
		
		return Redirect::route('dashboard')
					->with('flash_message', 'Update was successful.');
	}
	

}