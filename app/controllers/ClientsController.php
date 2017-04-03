<?php

use IntegrityInvoice\Repositories\ClientRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Services\Preference\OnetimeUpdater as OnetimeUpdater;
use IntegrityInvoice\Services\Client\Creator;
use IntegrityInvoice\Services\Client\Reader;
use IntegrityInvoice\Services\Client\Updater;
use IntegrityInvoice\Services\Client\Remover;
use IntegrityInvoice\Services\Client\BulkRemover;

class ClientsController extends BaseController {
	
	public $tenantID;
	public $client;
	public $userId;
	public $accountPlan;
	public $tenantVerification;
	public $limitReached;
	public $perPage;
	public $totalClients;
	public $clientLimit;
	public $subscripionHistory;
    public $preference;
	
	
	public function __construct(ClientRepositoryInterface $client, PaymentsHistoryRepositoryInterface $subscripionHistory, PreferenceRepositoryInterface $preference)
    {
    	$this->client = $client;
        $this->preference = $preference;
		$this->tenantID = Session::get('tenantID');
		$this->userId = Session::get('user_id');
		$this->subscripionHistory = $subscripionHistory;
		$this->accountPlan = Tenant::where('tenantID', '=', $this->tenantID)->pluck('account_plan_id');		
		$this->perPage = Preference::where('tenantID', '=', $this->tenantID)->pluck('page_record_number');	
		$this->totalClients = Client::where('tenantID', '=', $this->tenantID)->count();	
		$this->clientLimit = AccountPlan::where('id', '=', Tenant::where('account_plan_id', '=', $this->accountPlan)->pluck('account_plan_id'))->pluck('client_limit');		
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
    }

	
	public function index()
	{
		$searchquery = trim(Request::get('q'));
	
		// One-time setup check
		$onetimecheck = new OnetimeUpdater($this->preference, $this);
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please complete the settings below'); }
		
		// Pass in Client Model implementation and this class	
		$readerService = new Reader($this->client, $this);
		$clients = $readerService->readAll($searchquery);
		
		
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
		
		$total_default_currency_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)->where('currency_code','=', $preferences->currency_code)->sum('amount');
		
		// $total_other_currencies_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)->whereNotIn('currency_code','=', $preferences->currency_code)->groupBy('count')->sum('amount');
		
		//$group_totals = DB::select( DB::raw("SELECT currency_code, sum(amount) as total FROM expenses WHERE tenantID = '$this->tenantID' GROUP BY currency_code WITH ROLLUP"));
		
		$group_totals = DB::table('expenses')->select(DB::raw("currency_code, sum(amount) as total"))
                 ->groupBy('currency_code')
                 ->get();
		 
		$total_other_currencies_amount = 0;
		
		foreach($group_totals as $group_total){
			$total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
		}
	 
	 
		$all_totals_home_currency =  $total_default_currency_amount + $total_other_currencies_amount;
        $account_plan_id = $this->accountPlan;
		 
        return View::make('clients.index')
		   ->with('title', 'List of clients')
		   ->with(compact('clients'))
		   ->with('total_records', Client::count($searchquery))
		   ->with('searchquery', $searchquery)
           ->with('account_plan_id', $account_plan_id)
		   ->with('preferences', Preference::where('tenantID', '=', $this->tenantID)->first());
	}
	
	
	public function import()
	{
		return View::make('clients.import')
			->with('title', 'Import clients');
	}
	
	public function processImport()
	{
		 
	    if($this->totalClients >= $this->clientLimit){
			$this->limitReached = TRUE;
		}else{
			$this->limitReached = FALSE;
		}
		
		
		// SET 
		ini_set("auto_detect_line_endings", "1");
	
		$client_names = Client::where('tenantID', '=', $this->tenantID)->lists('company');
		
		$file = Input::file('clientscsv');		 
		 	
		// Extention Validation
		if(is_null($file))
		{ 
			return Redirect::route('importClients')->with('failed_flash_message', 'Please select a CSV file'); 
		}
  
	     if($file->getClientOriginalExtension() == "csv")
	     {
	  
	         $handle = fopen($file, "r");
	   
	   		 $row_count = 0;
	         while (($data = fgetcsv($handle, 1000, ",","\n")) !== FALSE)
	         {
	         	// Check if items is in the right format for item
	         	if($row_count == 0){
	         		
					if(count($data) != 11)
					{
	         			return Redirect::route('importClients')->with('failed_flash_message', 'File is not in the correct format, please check the format provided.'); 
	         		}
					
	         		if(trim($data[0]) != "Business Name" && trim($data[1]) != "Firstname" && trim($data[2]) != "Lastname" && trim($data[3]) != "Email" 
					&& trim($data[4]) != "Phone" && trim($data[5]) != "Address line 1" && trim($data[6]) != "Address Line 2"
					&& trim($data[7]) != "City" && trim($data[8]) != "County / State" && trim($data[9]) != "Post / Zip Code"
					&& trim($data[10]) != "Country")
					{
	         			return Redirect::route('importClients')->with('failed_flash_message', 'File is not in the correct format, please check the format provided.');
	         		}
							
			  
	         	}	         	
	         	
	         	$row_count ++;		 
			 
				if($row_count > 1)
				{
					// If the Item name does not exit already, this is not case sensitive
					 
					 if($this->limitReached == FALSE)
					 {
					 	
						if(!in_array($data[0], $client_names))
						{		  
							$creatorService = new Creator($this->client, $this);								
							$creatorService->create(array(
								'company' => $data[0],
								'add_1' => $data[5],
								'add_2' => $data[6],
								'postal_code' => $data[9],
								'city' => $data[7],
								'state' => $data[8],
								'country' => $data[10],							 
								'firstname' => $data[1],
								'lastname' => $data[2],
								'email' => $data[3],
								'phone' => $data[4],
								'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
								'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
								'tenantID' => $this->tenantID
							), FALSE);
		 
						} 
					 	
					 }else{
					 	return Redirect::route('clients')->with('flash_message', 'You have reached your client records limit.');
					 }
					
					
				} 

	         }

	         fclose($handle);
			 
			return Redirect::route('clients')->with('flash_message', 'Successfully imported data from the CSV file');
	     }
	     else
	     {
	     	return Redirect::route('clients')->with('failed_flash_message', 'Only a file of .csv extension is allowed');
		 }   
 
	}

	public function process_export()
	{
		$clients = Client::where('tenantID', '=', $this->tenantID)->get(array('company', 'firstname', 'lastname', 'email', 'phone', 'add_1', 'add_2', 'city', 'state', 'postal_code', 'country','notes'));
		 
		$filepath = public_path(). '/te_da/'.$this->tenantID.'/user_data/'.'clients.csv';
	    $file = fopen($filepath, 'w');
		
		$header = array('Company', 'Firstname', 'Lastname', 'Email', 'Phone', 'Address Line 1', 'Address line 2', 'City', 'State / County', 'Post code / Zip', 'Country', 'Notes');
		fputcsv($file, $header);
		
	    foreach ($clients as $client) {
	        fputcsv($file, $client->toArray());
	    }
	    fclose($file);
		 
		// Return excel format
		// return Excel::load($filepath)->convert('xls');
		
  		// Return CSV format
	    return Response::download($filepath);
	}	
	
	public function export()
	{
		return View::make('clients.export')->with('title', 'Export Clients Data');
	}
	
	

	 
	public function create()
	{
		// Restrictions
		if($this->totalClients >= $this->clientLimit){
			$this->limitReached = TRUE;
		}else{
			$this->limitReached = FALSE;
		}
		
		$tenant = Tenant::where('tenantID','=', $this->tenantID)->first();
		// Validate subscription
		if(!$this->subscripionHistory->validateSubscription($this->tenantID)){
			
		 $message =	'<p class="free_to_premium_message onexpired">Your subscription has expired, <a href="'. URL::route("subscription") .'">renew now</a>. </span>
			<span>or Get 1 month of premium subscription (for FREE) for every friend that signs up with your referral code below: </span>
			<span class="ref_code">'.$tenant->referral_code.'</span></p>';
		 
		    return Redirect::to('subscription/history')->with('failed_flash_message', $message);
		 
		}

		 return View::make('clients.create')
		->with('title', 'Add new client')
		->with('countries', Country::all())
		->with('limitReached', $this->limitReached);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{		
		$creator = new Creator($this->client, $this);		
		return $creator->create(array(
			'company' => trim(Input::get('company')),
			'add_1' =>Input::get('add_1'),
			'add_2' =>Input::get('add_2'),
			'postal_code' =>Input::get('postal_code'),
			'city' =>Input::get('city'),
			'state' =>Input::get('state'),
			'country' =>Input::get('country'),
			'tax_id' =>Input::get('tax_id'),
			'notes' =>Input::get('notes'),
			'firstname' =>Input::get('firstname'),
			'lastname' =>Input::get('lastname'),
			'email' =>Input::get('email'),
			'phone' =>Input::get('phone'),
			'firstname_secondary' =>Input::get('firstname_secondary'),
			'lastname_secondary' =>Input::get('lastname_secondary'),
			'email_secondary' =>Input::get('email_secondary'),
			'phone_secondary' =>Input::get('phone_secondary'),
			'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'tenantID' => $this->tenantID
		));
	
	}

    public function ajaxy_store()
    {
        // Verify if company does not already exists
        $inputEmail = trim(Input::get('email'));
        $existingClient = Client::where('tenantID', '=', $this->tenantID)->where('email', '=', $inputEmail)->first();
        if($existingClient){
            return "EmailExists";
        }

        $creator = new Creator($this->client, $this);
        return $creator->create(array(
            'company' => trim(Input::get('company')),
            'add_1' =>Input::get('add_1'),
            'add_2' =>Input::get('add_2'),
            'postal_code' =>Input::get('postal_code'),
            'city' =>Input::get('city'),
            'state' =>Input::get('state'),
            'country' =>Input::get('country'),
            'tax_id' =>Input::get('tax_id'),
            'notes' =>Input::get('notes'),
            'firstname' =>Input::get('firstname'),
            'lastname' =>Input::get('lastname'),
            'email' =>Input::get('email'),
            'phone' =>Input::get('phone'),
            'firstname_secondary' =>Input::get('firstname_secondary'),
            'lastname_secondary' =>Input::get('lastname_secondary'),
            'email_secondary' =>Input::get('email_secondary'),
            'phone_secondary' =>Input::get('phone_secondary'),
            'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
            'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
            'tenantID' => $this->tenantID
        ));
    }


	public function clientCreationFails($errors){
		
		return Redirect::route('create_client')->withErrors($errors)->withInput();
	}
	
	public function clientCreationSucceeds(){
		
		return Redirect::route('clients')
					->with('flash_message', 'New client was created successfully');
	}
 

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return View::make('clients.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$client = Client::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first();
		if(!$client){
			return Redirect::route('clients')->with('failed_flash_message', 'Invalid Client ID.');
		}
		
        return View::make('clients.edit')
		->with('title', 'Edit client')
		->with('countries', Country::all())
		->with('client', $client);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$id = Input::get('clientId');
	 
		$updateService = new Updater($this->client, $this);		
		return $updateService->update($id, array(
			'company' =>Input::get('company'),
			'add_1' =>Input::get('add_1'),
			'add_2' =>Input::get('add_2'),
			'postal_code' =>Input::get('postal_code'),
			'city' =>Input::get('city'),
			'state' =>Input::get('state'),
			'country' =>Input::get('country'),
			'tax_id' =>Input::get('tax_id'),
			'notes' =>Input::get('notes'),
			'firstname' =>Input::get('firstname'),
			'lastname' =>Input::get('lastname'),
			'email' =>Input::get('email'),
			'phone' =>Input::get('phone'),
			'firstname_secondary' =>Input::get('firstname_secondary'),
			'lastname_secondary' =>Input::get('lastname_secondary'),
			'email_secondary' =>Input::get('email_secondary'),
			'phone_secondary' =>Input::get('phone_secondary'),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
	}

	public function clientUpdateFails($id, $errors){
		
		return Redirect::route('edit_client', $id)->withErrors($errors)->withInput();
	}
	
	public function clientUpdateSucceeds($id){
		
		return Redirect::route('edit_client', $id)
					->with('flash_message', 'Updated successfully');
	}
	
	

	public function destroy($id)
	{
		$removerService = new Remover($this->client, $this);		
		return $removerService->remove($id);
	}
	
	public function deletebulk(){
		
		$bulkRemoverService = new BulkRemover($this->client, $this);

		$checkboxArray  = Input::get('checkbox');

		if(!empty($checkboxArray)){
							
			return $bulkRemoverService->remove($checkboxArray);	
		}
		else
		{
			return $this->clientBulkDeletionFails();
		}

	}
	
	public function clientDeletionFails(){
		
		return Redirect::route('clients')
					->with('failed_flash_message', 'Client was not deleted');
	}
	
	public function clientDeletionSucceeds(){
		
		return Redirect::route('clients')
					->with('flash_message', 'Client was deleted successfully');
	}
	
	public function clientBulkDeletionFails(){
		
		return Redirect::route('clients')
					->with('failed_flash_message', 'No client(s) was deleted');
	}
	
	public function clientBulkDeletionSucceeds(){
		
		return Redirect::route('clients')
					->with('flash_message', 'The client(s) was deleted successfully');
	}

}