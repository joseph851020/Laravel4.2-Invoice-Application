<?php

use IntegrityInvoice\Repositories\MerchantRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Services\Merchant\Creator;
use IntegrityInvoice\Services\Merchant\Reader;
use IntegrityInvoice\Services\Merchant\Updater;
use IntegrityInvoice\Services\Merchant\Remover;
use IntegrityInvoice\Services\Merchant\BulkRemover;


class MerchantsController extends BaseController {
	
	public $tenantID;
	public $merchant;
	public $userId;
	public $accountPlan;
	public $tenantVerification;
	public $perPage;
	public $totalMerchants;
	public $limitReached;
	public $merchantLimit;
	public $subscripionHistory;
 
	public function __construct(MerchantRepositoryInterface $merchant, PaymentsHistoryRepositoryInterface $subscripionHistory)
    {
    	$this->merchant = $merchant;
		$this->tenantID = Session::get('tenantID');
		$this->subscripionHistory = $subscripionHistory;
		$this->userId = Session::get('user_id');
    	$this->accountPlan = Tenant::where('tenantID', '=', $this->tenantID)->pluck('account_plan_id');		
		$this->perPage = Preference::where('tenantID', '=', $this->tenantID)->pluck('page_record_number');	
		$this->totalMerchants = Merchant::where('tenantID', '=', $this->tenantID)->count();		
		$this->merchantLimit = AccountPlan::where('id', '=', Tenant::where('account_plan_id', '=', $this->accountPlan)->pluck('account_plan_id'))->pluck('merchant_limit');		
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
	
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$searchquery = trim(Request::get('q'));
		
		// Redirect to dashboard if Registration has not been verified!
		// if($this->tenantVerification == 0){		 
			// return Redirect::route('dashboard')->with('failed_flash_message', 'Please check your email and verify your account');
		// }
		
		// Pass in merchant Model implementation and this class	
		$readerService = new Reader($this->merchant, $this);
		$merchants = $readerService->readAll();
 
        return View::make('merchants.index')
		   ->with('title', 'List of merchants')
		   ->with(compact('merchants'))
		   ->with('total_records', $this->totalMerchants)
		   ->with('searchquery', $searchquery)
		   ->with('preferences', Preference::where('tenantID', '=', $this->tenantID)->first());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
        // Restrictions
		if($this->totalMerchants >= $this->merchantLimit){
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
		
		return View::make('merchants.create')
		->with('title', 'Add new merchant')
		->with('limitReached', $this->limitReached);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$creatorService = new Creator($this->merchant, $this);		
		return $creatorService->create(array(
			'company' =>Input::get('company'),
			'add_1' =>Input::get('add_1'),
			// 'add_2' =>Input::get('add_2'),
			'postal_code' =>Input::get('postal_code'),
			'city' =>Input::get('city'),
			'state' =>Input::get('state'),
			'country' =>Input::get('country'),
			// 'tax_id' =>Input::get('tax_id'),
			'notes' =>Input::get('notes'),
			//'firstname' =>Input::get('firstname'),
			//'lastname' =>Input::get('lastname'),
			'email' =>Input::get('email'),
			'phone' =>Input::get('phone'),
			//'firstname_secondary' =>Input::get('firstname_secondary'),
			//'lastname_secondary' =>Input::get('lastname_secondary'),
			//'email_secondary' =>Input::get('email_secondary'),
			//'phone_secondary' =>Input::get('phone_secondary'),
			'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'tenantID' =>Session::get('tenantID')
		));
		
	}
	
	public function merchantCreationFails($errors){
		
		return Redirect::route('create_merchant')->withErrors($errors)->withInput();
	}
	
	public function merchantCreationSucceeds(){
		
		return Redirect::route('merchants')
					->with('flash_message', 'New merchant was created successfully');
	}
	
	
	public function import()
	{
		return View::make('merchants.import')
			->with('title', 'Import merchants');
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
	
		$merchant_names = Merchant::where('tenantID', '=', $this->tenantID)->lists('company');
		
		$file = Input::file('merchantscsv');		 
		 	
		// Extention Validation
		if(is_null($file))
		{ 
			return Redirect::route('importmerchants')->with('failed_flash_message', 'Please select a CSV file'); 
		}
  
	     if($file->getClientOriginalExtension() == "csv")
	     {
	  
	         $handle = fopen($file, "r");
	   
	   		 $row_count = 0;
	         while (($data = fgetcsv($handle, 1000, ",","\n")) !== FALSE)
	         {
	         	// Check if items is in the right format for item
	         	if($row_count == 0){
	         		
					if(count($data) != 13)
					{
	         			return Redirect::route('importmerchants')->with('failed_flash_message', 'File is not in the correct format, please check the format provided.'); 
	         		}
					
	         		if(trim($data[0]) != "Business Name" && trim($data[1]) != "Firstname" && trim($data[2]) != "Lastname" && trim($data[3]) != "Email" 
					&& trim($data[4]) != "Phone" && trim($data[5]) != "Address line 1" && trim($data[6]) != "Address Line 2"
					&& trim($data[7]) != "City" && trim($data[8]) != "County / State" && trim($data[9]) != "Post / Zip Code"
					&& trim($data[10]) != "Country")
					{
	         			return Redirect::route('importmerchants')->with('failed_flash_message', 'File is not in the correct format, please check the format provided.');
	         		}
							
			  
	         	}	         	
	         	
	         	$row_count ++;		 
			 
				if($row_count > 1)
				{
					// If the Item name does not exit already, this is not case sensitive
				  
				  if($this->limitReached == FALSE)
				  {
				  	if(!in_array($data[0], $merchant_names))
					{		  
						$creatorService = new Creator($this->merchant, $this);								
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
				  	return Redirect::route('merchants')->with('flash_message', 'You have reached your merchant records limit.');
					
				  }
				 
				} 

	         }

	         fclose($handle);
			 
			return Redirect::route('merchants')->with('flash_message', 'Successfully imported data from the CSV file');
	     }
	     else
	     {
	     	return Redirect::route('merchants')->with('failed_flash_message', 'Only a file of .csv extension is allowed');
		 }   
 
	}	


	public function export()
	{
		return View::make('merchants.export')->with('title', 'Export Merchants');
	}
	
	public function process_export()
	{
		$merchants = Merchant::where('tenantID', '=', $this->tenantID)->get(array('company', 'firstname', 'lastname', 'email', 'phone', 'add_1', 'add_2', 'city', 'state', 'postal_code', 'country','notes'));
		 
		$filepath = public_path(). '/te_da/'.$this->tenantID.'/user_data/'.'merchants.csv';
	    $file = fopen($filepath, 'w');
		
		$header = array('Company', 'Firstname', 'lastname', 'Email', 'Phone', 'Address Line 1', 'Address line 2', 'City', 'State / County', 'Post code / Zip', 'Country', 'Notes');
		fputcsv($file, $header);
		
	    foreach ($merchants as $merchant) {
	        fputcsv($file, $merchant->toArray());
	    }
	    fclose($file);
		
 		// Return excel format
		// return Excel::load($filepath)->convert('xls');
  		// Return CSV format
		 return Response::download($filepath);
	}
	

	 
	public function show($id)
	{
        return View::make('merchants.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        return View::make('merchants.edit')
		->with('title', 'Edit Merchant')
		->with('merchant', Merchant::find($id));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$id = Input::get('merchantId');
	 
		$updateService = new Updater($this->merchant, $this);		
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
	
	public function merchantUpdateFails($id, $errors){
		
		return Redirect::route('edit_merchant', $id)->withErrors($errors)->withInput();
	}
	
	public function merchantUpdateSucceeds($id){
		
		return Redirect::route('edit_merchant', $id)
					->with('flash_message', 'Updated successfully');
	}

	
	public function destroy($id)
	{
		$removerService = new Remover($this->merchant, $this);		
		return $removerService->remove($id);
	}
	
	public function deletebulk(){
		
		$bulkRemoverService = new BulkRemover($this->merchant, $this);

		$checkboxArray  = Input::get('checkbox');

		if(!empty($checkboxArray)){
							
			return $bulkRemoverService->remove($checkboxArray);	
		}
		else
		{
			return $this->merchantBulkDeletionFails();
		}

	}
	
	public function merchantDeletionFails(){
		
		return Redirect::route('merchants')
					->with('failed_flash_message', 'Merchant was not deleted');
	}
	
	public function merchantDeletionSucceeds(){
		
		return Redirect::route('merchants')
					->with('flash_message', 'Merchant was deleted successfully');
	}
	
	public function merchantBulkDeletionFails(){
		
		return Redirect::route('merchants')
					->with('failed_flash_message', 'No merchant(s) was deleted');
	}
	
	public function merchantBulkDeletionSucceeds(){
		
		return Redirect::route('merchants')
					->with('flash_message', 'The merchant(s) was deleted successfully');
	}

}