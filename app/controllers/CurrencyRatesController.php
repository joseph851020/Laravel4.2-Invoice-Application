<?php

use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Repositories\CurrencyRateRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Services\CurrencyRate\Creator;
use IntegrityInvoice\Services\CurrencyRate\Reader;
use IntegrityInvoice\Services\CurrencyRate\Updater;
use IntegrityInvoice\Services\CurrencyRate\Remover;

use Carbon\Carbon;

class CurrencyRatesController extends \BaseController {
	
	public $tenantID;
	public $userId;
	public $perPage;
	public $currencyRate;
	public $accountPlan;
	public $totalCurrencyRates;  
	public $tenantVerification;
	public $subscripionHistory;
	
	
	public function __construct(CurrencyRateRepositoryInterface $currencyRate, PaymentsHistoryRepositoryInterface $subscripionHistory)
    {
 		$this->currencyRate = $currencyRate;
		$this->tenantID = Session::get('tenantID');
		$this->subscripionHistory = $subscripionHistory;
		$this->userId = Session::get('user_id');
		$this->perPage = Preference::where('tenantID', '=', $this->tenantID)->pluck('page_record_number');
		$this->accountPlan = Tenant::where('tenantID', '=', $this->tenantID)->pluck('account_plan_id');
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
		$this->totalCurrencyRates = Expense::where('tenantID', '=', $this->tenantID)->count();
	 
    }

	/**
	 * Display a listing of currency_rates
	 *
	 * @return Response
	 */
	public function index()
	{
		
		// Pass in currencyRate Model implementation and this class	
		$readerService = new Reader($this->currencyRate, $this);
		$currency_rates = $readerService->readAll();
		
		$preferences = Preference::where('tenantID', '=', Session::get('tenantID'))->first();
 	   
		return View::make('currency_rates.index')
		 ->with('title', 'Currency Exchange Rates')
		 ->with(compact('currency_rates'))
		 ->with('total_records', CurrencyRate::where('tenantID','=', $this->tenantID)->orderBy('created_at')->count())		 
		 ->with('preferences', $preferences) 
		 ->with('home_currency', Currency::where('three_code', '=', $preferences->currency_code)->pluck('country_currency'));
	 
	}

	/**
	 * Show the form for creating a new currencyrate
	 *
	 * @return Response
	 */
	public function create()
	{
		$tenant = Tenant::where('tenantID','=', $this->tenantID)->first();
		// Validate subscription
		if(!$this->subscripionHistory->validateSubscription($this->tenantID)){
			
		 $message =	'<p class="free_to_premium_message onexpired">Your subscription has expired, <a href="'. URL::route("subscription") .'">renew now</a>. </span>
			<span>or Get 1 month of premium subscription (for FREE) for every friend that signs up with your referral code below: </span>
			<span class="ref_code">'.$tenant->referral_code.'</span></p>';
		 
		    return Redirect::to('subscription/history')->with('failed_flash_message', $message);
		 
		}
		
		$preferences = Preference::where('tenantID', '=', Session::get('tenantID'))->first();
		
		
		$default_currency = Preference::where('tenantID', '=',  $this->tenantID)->lists('currency_code');
		$currency_rates = CurrencyRate::where('tenantID', '=', $this->tenantID)->lists('currency_code');
		
		$currency_list = array();		
		if(count($currency_rates) > -1){				
			$currency_list = array_merge($default_currency, $currency_rates);		}
		else{
			$currency_list = $default_currency;
		}
		 
		 return View::make('currency_rates.create')
		 ->with('preferences', $preferences)		 
		 ->with('currencies', Currency::where('three_code', '=' , $preferences->currency_code)->get())
		 ->with('currency_list', $currency_list)
		 ->with('home_currency', Currency::where('three_code', '=', $preferences->currency_code)->pluck('country_currency'))
		 ->with('title', 'Add new currency exchange rate');
	 
		 
	}

	/**
	 * Store a newly created currencyrate in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		// Check that currency does not exist
		
		if($this->currencyRate->findByCurrencyCode($this->tenantID, Input::get('currency')))
		{	 
			return Redirect::route('create_currency_rate')->with('failed_flash_message', 'Currency '. Input::get('currency') .' already exists.')->withInput();
		}
		 
		$creatorService = new Creator($this->currencyRate, $this);
		 
		$unit_exchange_rate = str_replace('&', '', Input::get('unit_exchange_rate'));
		$unit_exchange_rate = str_replace('$', '', Input::get('unit_exchange_rate'));
		$unit_exchange_rate = str_replace('£', '', Input::get('unit_exchange_rate'));
		$unit_exchange_rate = str_replace('%', '', Input::get('unit_exchange_rate'));
		$unit_exchange_rate = str_replace('*', '', Input::get('unit_exchange_rate'));
		$unit_exchange_rate = str_replace('€', '', Input::get('unit_exchange_rate'));
		$unit_exchange_rate = str_replace(',', '', Input::get('unit_exchange_rate'));
		 		
		return $creatorService->create(array(
			'unit_exchange_rate' => $unit_exchange_rate,			 
			'country_currency' => Currency::where('three_code', '=', Input::get('currency'))->pluck('country_currency'),	
			'currency_code' => Input::get('currency'),		 	
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),			 
			'tenantID' => $this->tenantID
		));
	}
	
	public function currencyRateCreationFails($errors){		
		return Redirect::route('create_currency_rate')->withErrors($errors)->withInput();
	}
	
	public function currencyRateCreationSucceeds(){		
		return Redirect::route('currency_rates')->with('flash_message', 'New currency rate was created successfully');
	}

	/**
	 * Display the specified currencyrate.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$currencyrate = CurrencyRate::findOrFail($id);

		return View::make('currency_rates.show', compact('currencyrate'));
	}

	/**
	 * Show the form for editing the specified currencyrate.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($currencyCode)
	{
		if($currencyCode == "" || $currencyCode == NULL){
			return Redirect::route('currency_rates');
		}
		
		$currencyrate = CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code', '=', $currencyCode)->first();		
		
		if($currencyrate == NULL){
			return Redirect::route('currency_rates');
		}
 
		$preferences = Preference::where('tenantID', '=', Session::get('tenantID'))->first();		 
	  
		 return View::make('currency_rates.edit')
		 ->with('preferences', $preferences)
		 ->with(compact('currencyrate'))	 
		 ->with('home_currency', Currency::where('three_code', '=', $preferences->currency_code)->pluck('country_currency'))
		 ->with('title', 'Edit currency rate');
	}

	/**
	 * Update the specified currencyrate in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$unit_exchange_rate = trim(Input::get('unit_exchange_rate'));
		if($unit_exchange_rate == "" || $unit_exchange_rate == NULL || $unit_exchange_rate == 0){
			return Redirect::route('currency_rates')->with('failed_flash_message', 'Exchange rate could not be updated.');
		}	
		
		$currencyCode = trim(Input::get('currency_code'));		 
		if($currencyCode == "" || $currencyCode == NULL){
			return Redirect::route('currency_rates');
		}		
		
		$currencyrate = CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code', '=', $currencyCode)->first();			 
		
		if($currencyrate == NULL){
			return Redirect::route('currency_rates');
		}
		
		$data = array(
			'unit_exchange_rate' => $unit_exchange_rate
		);
		 
		$currencyrate->update($data);
		return Redirect::route('currency_rates')->with('flash_message', 'Currency exchnage rate was successfully updated.');
	}


	public function get_currency_exchange_rate()
	{
		$from = Input::get('from_currency');
		$to = Input::get('to_currency');
		
		return AppHelper::convert_currency($from, $to, 1);
	}

	/**
	 * Remove the specified currencyrate from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($currency_code)
	{
		
		// Check that No invoice is issued in this currency
		if(Invoice::where('tenantID', '=', $this->tenantID)->where('currency_code', '=', $currency_code)->first())
		{
			return Redirect::route('currency_rates')->with('failed_flash_message', 'Currency rate was not deleted because you have an invoice billed in '.$currency_code. '. Remove that invoice first.');
		}
		
		// Check that No expense is in this currency
		if(Expense::where('tenantID', '=', $this->tenantID)->where('currency_code', '=', $currency_code)->first())
		{
			return Redirect::route('currency_rates')->with('failed_flash_message', 'Currency rate was not deleted because you have an expense recorded in '.$currency_code. '. Remove that invoice first.');
		}
		 
		$removerService = new Remover($this->currencyRate, $this);		
		return $removerService->remove($currency_code);
	}
	
	public function currencyRateDeletionFails(){
		
		return Redirect::route('currency_rates')
					->with('failed_flash_message', 'currency rates was not deleted');
	}
	
	public function currencyRateDeletionSucceeds(){		
		return Redirect::route('currency_rates')
					->with('flash_message', 'currency_rates was deleted successfully');
	}

}
