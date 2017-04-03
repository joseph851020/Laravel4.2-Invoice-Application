<?php
use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Repositories\ExpenseRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Services\Expense\Creator;
use IntegrityInvoice\Services\Expense\Reader;
use IntegrityInvoice\Services\Expense\Updater;
use IntegrityInvoice\Services\Expense\Remover;
use IntegrityInvoice\Services\Expense\BulkRemover;
use Illuminate\Filesystem;
use Carbon\Carbon;

class ExpensesController extends BaseController {
	
	public $tenantID;
	public $userId;
	public $perPage;
	public $expense;
	public $accountPlan;
	public $totalExpenses;
	public $expenseLimit;
	public $limitReached;
	public $tenantVerification;
	public $subscripionHistory;
    public $dateFormat;

	
	public function __construct(ExpenseRepositoryInterface $expense, PaymentsHistoryRepositoryInterface $subscripionHistory)
    {
 		$this->expense = $expense;
		$this->tenantID = Session::get('tenantID');
		$this->subscripionHistory = $subscripionHistory;
		$this->userId = Session::get('user_id');
		$this->perPage = Preference::where('tenantID', '=', $this->tenantID)->pluck('page_record_number');
		$this->accountPlan = Tenant::where('tenantID', '=', $this->tenantID)->pluck('account_plan_id');
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
		$this->totalExpenses = Expense::where('tenantID', '=', $this->tenantID)->count();
        $this->date_format = Preference::where('tenantID', '=',  $this->tenantID)->pluck('date_format');
		$this->expenseLimit = AccountPlan::where('id', '=', Tenant::where('account_plan_id', '=', $this->accountPlan)->pluck('account_plan_id'))->pluck('expense_limit');
  
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{ 
		// Pass in expense Model implementation and this class	
		$readerService = new Reader($this->expense, $this);
		$expenses = $readerService->readAll();
		
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
		
		$total_default_currency_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)->where('currency_code','=', $preferences->currency_code)->sum('amount');
		
		// $total_other_currencies_amount = DB::table('expenses')->where('tenantID','=', $this->tenantID)->whereNotIn('currency_code','=', $preferences->currency_code)->groupBy('count')->sum('amount');
		
		//$group_totals = DB::select( DB::raw("SELECT currency_code, sum(amount) as total FROM expenses WHERE tenantID = '$this->tenantID' GROUP BY currency_code WITH ROLLUP"));
		
		
		$tenant_currencies = DB::table('expenses')->where('tenantID','=', $this->tenantID)->distinct()->lists('currency_code');		
		if(count($tenant_currencies) < 1){ $tenant_currencies = array(1); }	 	
		
		$group_totals = DB::table('expenses')->select(DB::raw("currency_code, sum(amount) as total"))->where('tenantID','=', $this->tenantID)
				 ->whereIn('expenses.currency_code', $tenant_currencies)
                 ->groupBy('currency_code')->get();
		 
		 
		$total_other_currencies_amount = 0;
		
		foreach($group_totals as $group_total){
			$total_other_currencies_amount += CurrencyRate::where('tenantID', '=', $this->tenantID)->where('currency_code','<>', $preferences->currency_code)->where('currency_code', '=', $group_total->currency_code)->pluck('unit_exchange_rate') * $group_total->total;
		}
	 
	 
		$all_totals_home_currency =  round($total_default_currency_amount + $total_other_currencies_amount, 2);
		
  
		return View::make('expenses.index')
		 ->with('title', 'Expenses')
		 ->with(compact('expenses'))
		 ->with('total_records', Expense::where('tenantID','=', $this->tenantID)->orderBy('created_at')->count())
		 ->with('total_amount', $all_totals_home_currency)
		 ->with('categories', ExpenseCategory::getAll())
		 ->with('preferences', $preferences);   
	  
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{ 
		// Restrictions
		if($this->totalExpenses >= $this->expenseLimit){
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
		
		$preference = Preference::where('tenantID', '=',  $this->tenantID)->first();
	 
		$default_currency = Preference::where('tenantID', '=',  $this->tenantID)->lists('currency_code');
		$currency_rates = CurrencyRate::where('tenantID', '=', $this->tenantID)->lists('currency_code');
		
		$currency_list = array();		
		if(count($currency_rates) > -1){				
			$currency_list = array_merge($default_currency, $currency_rates);		}
		else{
			$currency_list = $default_currency;
		}
		

		 return View::make('expenses.create')
		 ->with('preferences', $preference)
		 ->with('merchants',  Merchant::getAll($this->tenantID))
		 ->with('categories', ExpenseCategory::getAll())
		 ->with('currency_list', $currency_list)
		 ->with('title', 'Add new expense')
		 ->with('limitReached', $this->limitReached);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{	
		$creatorService = new Creator($this->expense, $this);
		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
	 
		// Validate file 		 
		$input = Input::all();
		
		$file_attached = false;
		
		if(Input::file('file')){
			
			  $file_attached = true;
			
			  $rules = array(
	              'file' => 'mimes:jpg,jpeg,png,doc,docx,xls,xlsx,pdf,ppt,txt,zip|max:2000|required'            
	          );
		  
	         $validation = Validator::make($input, $rules);
			 
			 if($validation->fails()){
				return Redirect::route('create_expense')->withErrors($validation)->withInput();
			 }else{
			 
			 }
			
		}
       
		
		$amount = str_replace('&', '', Input::get('amount'));
		$amount = str_replace('$', '', Input::get('amount'));
		$amount = str_replace('£', '', Input::get('amount'));
		$amount = str_replace('%', '', Input::get('amount'));
		$amount = str_replace('*', '', Input::get('amount'));
		$amount = str_replace('€', '', Input::get('amount'));
		$amount = str_replace(',', '', Input::get('amount'));
		
		if(Input::get('newmerchant') != "" && Input::get('newmerchant') != NULL){
			 
			 // Add new merchant and retrieve the ID;
			 $newmerchant = new Merchant;
			 $newmerchant->tenantID = $this->tenantID;	
			 $newmerchant->company = Input::get('newmerchant');			
			 $newmerchant->save();

			 if($newmerchant->id != NULL){
			  	$merchant_id = $newmerchant->id;
			  }
			 else
			 {
			 	return Redirect::route('create_expense')->with('failed_flash_message', 'Unable to create new merchant')->withInput();
			 }
	 
		}else{
			
			$merchant_id = Input::get('merchant_id');
		}
	 			
		return $creatorService->create(array(
			'amount' => $amount,				
			'merchant_id' => $merchant_id,
			'ref_no' =>Input::get('ref'),
			'note' => Input::get('note'),			 
			'tax1_val' => Input::get('tax1_val'),
			'currency_code' => Input::get('currency_code'),
			'category_id' =>Input::get('category_id'),
			'expense_date' => AppHelper::convert_to_mysql_yyyymmdd(Input::get('created_at'), $preferences->date_format),	
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(), 
			'user_id' => $this->userId,
			'tenantID' =>Session::get('tenantID')
		));
		 
	 
	}
	
	
	public function expenseCreationFails($errors){
		
		return Redirect::route('create_expense')->withErrors($errors)->withInput();
	}
	
	public function expenseCreationSucceeds($expense){
		 
		// upload file and update file
		if(Input::file('file')){
				
			// Validation passed			     
	        $destinationPath = public_path().'/te_da/'.$this->tenantID.'/attachments/expenses/';		 			         
	        $filename = "file". $expense->id. "_".Input::file('file')->getClientOriginalName();	 
			$encryp_name = AppHelper::encrypt($filename, $this->tenantID);
		
		    $uploadSuccess = Input::file('file')->move($destinationPath, $filename);
			 
			if($uploadSuccess){				
				// Update Expense with file encryp_name				
				$updateService = new Updater($this->expense, $this);
				$updateService->update_no_redirect($expense->id, array(			 
						'file' => $encryp_name				 
			    )); 
			}				 
	 
		}
		
		return Redirect::route('expenses')
					->with('flash_message', 'New expense was created successfully');
	}
	
	
	public function download_file()
	{
		$id = (int)Request::segment(2);
		 
		$expense = Expense::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first();  
		$file = $expense->file;
		 
		if(!is_int($id)  || $file == NULL || $file == ""){
			return Redirect::route('expenses')->with('failed_flash_message', 'File name is invalid.');
		}
	 
		$pathToFile = public_path().'/te_da/'.$this->tenantID.'/attachments/expenses/'. Apphelper::decrypt($file, $expense->tenantID);
		
		if(file_exists($pathToFile)){		
			return Response::download($pathToFile);
		}else{
			return Redirect::route('edit_expense', $id)->with('failed_flash_message', 'File could not be downloaded.');
		}
	}
	
	
	public function remove_file()
	{
		$id = (int)Request::segment(2);
		
		$expense = Expense::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first(); 
		 
		if($expense->file != NULL && $expense->file != ""){
			// Delete file
			$pathToFile = public_path().'/te_da/'.$this->tenantID.'/attachments/expenses/'. Apphelper::decrypt($expense->file, $expense->tenantID);
			
			if(file_exists($pathToFile)){
				File::delete($pathToFile);
			 }
			
			// Update Invoice with file encryp_name				
			$updateService = new Updater($this->expense, $this);
			$updateService->update_no_redirect($id, array(			 
					'file' => NULL				 
		    )); 
		} 
		
		return Redirect::route('edit_expense', $id)
					->with('flash_message', 'Attached file was successfully removed.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        return View::make('expenses.show');
	}
	
	
	public function import()
	{
		return View::make('expenses.import')
			->with('title', 'Import expenses');
	}
	
	public function processImport()
	{
		// Restrictions
		if($this->totalExpenses >= $this->expenseLimit){
			$this->limitReached = TRUE;
		}else{
			$this->limitReached = FALSE;
		}
		

		// SET 
		ini_set("auto_detect_line_endings", "1");
	 
		$file = Input::file('expensescsv');
		 
		 	
		// Extention Validation
		if(is_null($file))
		{ 
			return Redirect::route('importExpenses')->with('failed_flash_message', 'Please select a CSV file'); 
		}

		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
  
	     if($file->getClientOriginalExtension() == "csv")
	     {
	  
	         $handle = fopen($file, "r");
	   
	   		 $row_count = 0;
	         while (($data = fgetcsv($handle, 1000, ",","\n")) !== FALSE)
	         {
	         	// Check if items is in the right format for item
	         	if($row_count == 0){
	         		
					if(count($data) != 6){						
						return Redirect::route('importExpenses')->with('failed_flash_message', 'File is not in the correct format, please check.');	         			
	         		}
					
	         		if(trim($data[0]) != "Date" && trim($data[1]) != "Details / Note" && trim($data[2]) != "Amount" && trim($data[3]) != "Seller / Merchant" && trim($data[4]) != "Ref. No" && trim($data[5]) != "Currency Code"){	         			
						return Redirect::route('importExpenses')->with('failed_flash_message', 'File is not in the correct format, please check.'); 
	         		}
	         	}//		         	
	         	
	         	$row_count ++;		 
			 
				if($row_count > 1)
				{
					
					 if($this->limitReached == FALSE)
					 {
					 	$merchant_id;
					
						if($data[3] != "" && $data[3] != NULL){
							
							// Search for the Merchant if exists
							$match_merchant = Merchant::where('tenantID', '=', $this->tenantID)->where('company', 'LIKE', '%'.trim($data[3]).'%')->first();
							
							if($match_merchant){
								$merchant_id = $match_merchant->id;
							}else{
								// Add new merchant and retrieve the ID;
								 $newmerchant = new Merchant;
								 $newmerchant->tenantID = $this->tenantID;	
								 $newmerchant->company = $data[3];			
								 $newmerchant->save();
					
								 if($newmerchant->id != NULL){
								  	$merchant_id = $newmerchant->id;
								  }
								
							}
				  
						}else{
							
						   $merchant_id = NULL;
						}
			 
					 
						$myprice = str_replace('&', '', $data[2]);
						$myprice = str_replace('$', '', $data[2]);
						$myprice = str_replace('£', '', $data[2]);
						$myprice = str_replace('%', '', $data[2]);
						$myprice = str_replace('*', '', $data[2]);
						$myprice = str_replace('€', '', $data[2]);
						$myprice = str_replace('¥', '', $data[2]);
						$myprice = str_replace('₦', '', $data[2]);	
					    
						$note_details = trim(preg_replace('/[\"]+/', '"', $data[1]),'"');	
						$seller = trim(preg_replace('/[\"]+/', '"', $data[3]),'"');
					 
						$date = AppHelper::convert_to_mysql_yyyymmdd($data[0], $preferences->date_format);				
						$date = $date .' 00:00:00';
					 
						$creatorService = new Creator($this->expense, $this);
					    $creatorService->create(array(
							'amount' => $myprice,
							'note' => $note_details,					 
							'category_id' => 1,
							'merchant_id' => $merchant_id,
							'ref_no' => $data[4],
							'currency_code' => trim($data[5]),
							'expense_date' => Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString(),				 						
							'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString(),
							'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString(),
							'user_id' => $this->userId,
							'tenantID' => $this->tenantID
							 
						), FALSE);
						
					 }else{
					 	return Redirect::route('expenses')->with('flash_message', 'You have reached your expense records limit.');
					 }
					 
				} // Row count

	         }

	         fclose($handle);
			 
			return Redirect::route('expenses')->with('flash_message', 'Successfully imported expenses.');
	     }
	     else
	     {
	     	return Redirect::route('import')->with('failed_flash_message', 'Only a file of .csv extension is allowed');
		 }   
 
	}

	public function export()
	{
		return View::make('expenses.export')->with('title', 'Export Expenses');
	}
	
	
	public function process_export()
	{	 
		$expenses = DB::table('expenses')->join('expense_categories', 'expenses.category_id', '=', 'expense_categories.id')
		->where('expenses.tenantID', '=', $this->tenantID)
		->get(array('expenses.created_at', 'expenses.note', 'expense_categories.expense_name', 'expenses.ref_no', 'expenses.currency_code', 'expenses.amount', 'expenses.tax1_val'));
		
		//dd($expenses[0]->toArray());
		// Convert to array due to using Fluent which does not support toArray()
		$expenses = json_decode(json_encode((array) $expenses), true);
		 
		// $expenses = Expense::where('tenantID', '=', $this->tenantID)->get(array('created_at', 'note', 'category_id', 'ref_no', 'tax1_val'));
		 
		$filepath = public_path(). '/te_da/'.$this->tenantID.'/user_data/'.'expenses.csv';
	    $file = fopen($filepath, 'w');
		
		$header = array('Date', 'Details / Note', 'Category', 'Ref No', 'Currency code', 'amount', 'Tax');
		fputcsv($file, $header);
		
	    foreach ($expenses as $expense) {
	        fputcsv($file, $expense);
	    }
	    fclose($file);
		 
		// Return excel format
		// return Excel::load($filepath)->convert('xls');
		
  		// Return CSV format
	    return Response::download($filepath);
	}
	

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{ 
		$expense = Expense::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first();
		$tenant = Tenant::where('tenantID','=', $this->tenantID)->first();
		
		if(!$expense){
			return Redirect::route('expenses')->with('failed_flash_message', 'Invalid Expense ID.');
		}
		
		 
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
	 
		$default_currency = Preference::where('tenantID', '=',  $this->tenantID)->lists('currency_code');
		$currency_rates = CurrencyRate::where('tenantID', '=', $this->tenantID)->lists('currency_code');
		
		$currency_list = array();		
		if(count($currency_rates) > -1){				
			$currency_list = array_merge($default_currency, $currency_rates);		}
		else{
			$currency_list = $default_currency;
		}
  	 
		 return View::make('expenses.edit')
		 ->with('preferences', $preferences)
		 ->with('merchants',  Merchant::getAll($this->tenantID))
		 ->with('categories', ExpenseCategory::getAll())
		 ->with('currency_list', $currency_list)
		 ->with(compact('expense'))
		 ->with('title', 'Edit expense');
	}
	
	
	public function update()
	{
		$id = Input::get('expenseId');
		
		// Validate file 		 
		$input = Input::all();
		
		$file_attached = false;
		
		if(Input::file('file')){
			
			  $file_attached = true;
			
			  $rules = array(
	              'file' => 'mimes:jpg,jpeg,png,doc,docx,xls,xlsx,pdf,ppt,txt,zip|max:2000|required'            
	          );
		  
	         $validation = Validator::make($input, $rules);
			 
			 if($validation->fails()){
				return Redirect::route('edit_expense', $id)->withErrors($validation)->withInput();
			 }else{
			 
			 }
			
		}
	 
		
		if(Input::get('newmerchant') != "" && Input::get('newmerchant') != NULL){
			 
			 // Add new merchant and retrieve the ID;
			 $newmerchant = new Merchant;
			 $newmerchant->tenantID = $this->tenantID;	
			 $newmerchant->company = Input::get('newmerchant');			
			 $newmerchant->save();

			 if($newmerchant->id != NULL){
			  	$merchant_id = $newmerchant->id;
			  }
			 else
			 {
			 	return Redirect::route('edit_expense', $id)->with('failed_flash_message', 'Unable to create new merchant')->withInput();
			 }
	 
		}else{
			
			$merchant_id = Input::get('merchant_id');
		}
		 
		$amount = str_replace('&', '', Input::get('amount'));
		$amount = str_replace('$', '', Input::get('amount'));
		$amount = str_replace('£', '', Input::get('amount'));
		$amount = str_replace('%', '', Input::get('amount'));
		$amount = str_replace('*', '', Input::get('amount'));
		$amount = str_replace('€', '', Input::get('amount'));
		$amount = str_replace(',', '', Input::get('amount'));
		
		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		
		$date = AppHelper::convert_to_mysql_yyyymmdd(Input::get('created_at'), $preferences->date_format);					
		$date = $date .' 00:00:00';
		 
		$updateService = new Updater($this->expense, $this);		
		return $updateService->update($id, array(
				'amount' => $amount,				
				'merchant_id' => $merchant_id,
				'ref_no' =>Input::get('ref'),
				'note' => Input::get('note'), 
				'category_id' =>Input::get('category_id'),	
				'currency_code' =>Input::get('currency_code'),			
				'created_at' => Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString(),
				'updated_at' => Carbon::createFromFormat('Y-m-d H:i:s', $date)->toDateTimeString(),
				'user_id' => $this->userId
		));
		
	}

	public function expensetUpdateFails($id, $errors){
		
		return Redirect::route('edit_expense', $id)->withErrors($errors)->withInput();
	}
	
	public function expenseUpdateSucceeds($id){
		
		$id = (int)$id; 
		 
		// upload file and update file
		if(Input::file('file')){
		 
			// Validation passed			     
	        $destinationPath = public_path().'/te_da/'.$this->tenantID.'/attachments/expenses/';		 			         
	        $filename = "file". $id. "_".Input::file('file')->getClientOriginalName();	 
			$encryp_name = AppHelper::encrypt($filename, $this->tenantID);
		
		    $uploadSuccess = Input::file('file')->move($destinationPath, $filename);
			 
			if($uploadSuccess){				
				// Update Expense with file encryp_name				
				$updateService = new Updater($this->expense, $this);
				$updateService->update_no_redirect($id, array(			 
						'file' => $encryp_name				 
			    )); 
			}				 
	 
		}
		 
		return Redirect::route('edit_expense', $id)
					->with('flash_message', 'Updated successfully');
	}
 

	public function destroy($id)
	{
		$removerService = new Remover($this->expense, $this);
		
		$id = (int)$id;
		
		$expense = Expense::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first(); 
		 
		if($expense->file != NULL && $expense->file != ""){
			// Delete file
			$pathToFile = public_path().'/te_da/'.$this->tenantID.'/attachments/expenses/'. Apphelper::decrypt($expense->file, $expense->tenantID);
			
			if(file_exists($pathToFile)){
				File::delete($pathToFile);
			 } 
		} 
		
		return $removerService->remove($id);
	}
	
	public function deletebulk(){
		
		$bulkRemoverService = new BulkRemover($this->expense, $this);

		$checkboxArray  = Input::get('checkbox');

		if(!empty($checkboxArray)){
							
			return $bulkRemoverService->remove($checkboxArray);	
		}
		else
		{
			return $this->expenseBulkDeletionFails();
		}

	}
	
	public function expenseDeletionFails(){
		
		return Redirect::route('expenses')
					->with('failed_flash_message', 'expense was not deleted');
	}
	
	public function expenseDeletionSucceeds(){
		
		return Redirect::route('expenses')
					->with('flash_message', 'expense was deleted successfully');
	}
	
	
	public function expenseBulkDeletionFails(){
		
		return Redirect::route('expenses')
					->with('failed_flash_message', 'No expense(s) was deleted');
	}
	
	public function expenseBulkDeletionSucceeds(){
		
		return Redirect::route('expenses')
					->with('flash_message', 'The expense(s) was deleted successfully');
	}



    //////////////////////////////////////////////////////////////////////
    ////////// RECURRING  //////////////////////////////////

    public function expense_recurring($id){

        $id = $id;

        if($id == null || $id == ""){
            return Redirect::back()->with('failed_flash_message', 'Invalid Expense ID');
        }

        // Check that expense ID exists
        if(Expense::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first() == null){
            return Redirect::back()->with('failed_flash_message', 'Invalid Expense ID');
        }

        $recur_schedule = trim(Input::get('recur_schedule'));
        $next_recurring_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('next_recurring_date')), $this->date_format);
        $last_recurring_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('last_recurring_date')), $this->date_format);
        $recur_active_status = 0;

        // Validation

        // Frequency of recurring
        if($recur_schedule == null || $recur_schedule == ""){
            return Redirect::route('edit_expense', $id)->with('failed_flash_message', 'Recurring frequency not set');
        }

        // next recurring date
        if($next_recurring_date == null || $next_recurring_date == ""){
            return Redirect::route('edit_expense', $id)->with('failed_flash_message', 'Next recurring date not set');
        }

        // Last recurring date
        if($last_recurring_date == null || $last_recurring_date == ""){
            return Redirect::route('edit_expense', $id)->with('failed_flash_message', 'Last recurring date not set');
        }

        if(Input::get('recur_status')){
            $recur_active_status = 1;
        }else{
            $recur_active_status = 0;
        }

        $updateService = new Updater($this->expense, $this);
        $updateService->update_no_redirect($id, array(
            'recurring' => 1,
            'recur_schedule' => $recur_schedule,
            'recurring_start_date' => $next_recurring_date,
            'recur_next_date' => $next_recurring_date,
            'recurring_end_date' => $last_recurring_date,
            'recur_status' => $recur_active_status
        ));

        return Redirect::route('edit_expense', $id)->with('flash_message', 'Recurring options was successfully set on this expense.');
    }

    public function remove_recurring($id){
        if($id == null || $id == ""){
            return Redirect::back()->with('failed_flash_message', 'Invalid Expense ID');
        }

        // Check that expense ID exists
        if(Invoice::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first() == null){
            return Redirect::back()->with('failed_flash_message', 'Invalid Expense ID');
        }

        $updateService = new Updater($this->expense, $this);
        $updateService->update_no_redirect($id, array(
            'recurring' => 0,
            'recur_schedule' => NULL,
            'recurring_start_date' => NULL,
            'recur_next_date' => NULL,
            'recurring_end_date' => NULL,
            'recur_status' => 0
        ));

        return Redirect::route('edit_expense', $id)->with('flash_message', 'Recurring was deleted on this expense.');
    }


}