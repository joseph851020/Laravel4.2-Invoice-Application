<?php
use IntegrityInvoice\Utilities\AppHelper as AppHelper;
use IntegrityInvoice\Repositories\InvoiceRepositoryInterface;
use IntegrityInvoice\Repositories\PreferenceRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Services\Invoice\Creator;
use IntegrityInvoice\Services\Invoice\QuoteConverter;
use IntegrityInvoice\Services\Preference\DiscountUpdater;
use IntegrityInvoice\Services\Preference\TaxUpdater;
// use IntegrityInvoice\Services\Invoice\Reader;
use IntegrityInvoice\Services\Invoice\Updater;
use IntegrityInvoice\Services\Preference\OnetimeUpdater as OnetimeUpdater;
use IntegrityInvoice\Mailers\AppMailer;
use Carbon\Carbon;
use Illuminate\Filesystem;
use IntegrityInvoice\Services\Invoice\Remover;

require_once base_path('vendor/knplabs/knp-snappy/src/autoload.php');
use Knp\Snappy\Pdf;

class InvoicesController extends BaseController {
	
	public $tenantID;
	public $invoice;
	public $accountPlan;
	public $totalInvoicesThisMonth;
	public $totalQuotesThisMonth;
	public $monthlyInvoiceLimit;
	public $monthlQuoteLimit;
	public $tenantVerification;
	public $dateFormat;
	public $limitReached;
	public $perPage;
	public $preference;
	public $subscripionHistory;
	private $mailer;
	
	function __construct(InvoiceRepositoryInterface $invoice, PreferenceRepositoryInterface $preference, PaymentsHistoryRepositoryInterface $subscripionHistory, AppMailer $mailer)
    {
        $this->invoice = $invoice;
		$this->tenantID = Session::get('tenantID');
		$this->preference = $preference;
		$this->subscripionHistory = $subscripionHistory;
		$this->mailer = $mailer;
		  
		$this->accountPlan = Tenant::where('tenantID', '=',  $this->tenantID)->pluck('account_plan_id');
		$this->date_format = Preference::where('tenantID', '=',  $this->tenantID)->pluck('date_format');
		$this->perPage = Preference::where('tenantID', '=',  $this->tenantID)->pluck('page_record_number');
		//$this->total_invoice_last_thirtydays = $this->Invoice_model->total_invoices_last_thirty_days();
		$this->totalInvoicesThisMonth = Invoice::total_invoices_this_month();
		$this->totalQuotesThisMonth = Invoice::total_quotes_this_month();
		$this->monthlyInvoiceLimit = AccountPlan::where('id', '=', Tenant::where('account_plan_id', '=', $this->accountPlan)->pluck('account_plan_id'))->pluck('invoice_limit');
		$this->monthlQuoteLimit = AccountPlan::where('id', '=', Tenant::where('account_plan_id', '=', $this->accountPlan)->pluck('account_plan_id'))->pluck('quote_limit');
		
		$this->tenantVerification = Tenant::where('tenantID', '=',  $this->tenantID)->pluck('verified');
		// Redirect to dashboard if Registration has not been verified!
		if($this->tenantVerification == 0){
			Redirect::route('dashboard');
		}
	
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
			
		// One-time setup check
		$onetimecheck = new OnetimeUpdater($this->preference, $this);
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please ensure that your currency and time zone options are set.'); }
			
		$searchquery = trim(Request::get('q'));		
		$searchquery = ltrim($searchquery, '0');
		 
		$filter = trim(Request::get('filter'));	
		
		if($filter != "unpaid" && $filter != "partpaid"){
			$filter = "undefined";
		}
		
		$filter_label;
		 
		
		// If it's a quote
		if(str_contains(Request::url(), 'quote'))
		{
			$request_type = 'quote'; 
			$title = 'List of quotes';
			
			if($request_type == 'quote'){$quote = 1; }else{ $quote = 0; }
			$total_records = Invoice::count($searchquery, $quote);
			
			$invoices = $searchquery
					? Invoice::where('tenantID', '=',  $this->tenantID)->where('quote', '=', 1)->where('tenant_'.$request_type.'_id', 'LIKE',  "%$searchquery%")->orWhere('client_name', 'LIKE',  "%$searchquery%")->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage)
					: Invoice::where('tenantID', '=',  $this->tenantID)->where('quote', '=', 1)->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage);
		}
		else
		{
			
			$request_type = 'invoice';
			$title = 'List of invoices';
			
			if($request_type == 'quote'){$quote = 1; }else{ $quote = 0; }
			
			// If filter by unpaid invoices
			if($filter == 'unpaid'){
				
				$filter_label = "Unpaid";
				$total_records = Invoice::count_filter(0);
				
				$invoices = $searchquery
					? Invoice::where('tenantID', '=',  $this->tenantID)->where('payment', '=', 0)->where('quote', '=', 0)->where('tenant_'.$request_type.'_id', 'LIKE',  "%$searchquery%")->orWhere('client_name', 'LIKE',  "%$searchquery%")->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage)
					: Invoice::where('tenantID', '=',  $this->tenantID)->where('payment', '=', 0)->where('quote', '=', 0)->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage);
			}
			
			
			// If filter by partpaid invoices
			if($filter == 'partpaid'){
				
				$filter_label = "Part-paid";
				$total_records = Invoice::count_filter(1);
				
				$invoices = $searchquery
					? Invoice::where('tenantID', '=',  $this->tenantID)->where('payment', '=', 1)->where('quote', '=', 0)->where('tenant_'.$request_type.'_id', 'LIKE',  "%$searchquery%")->orWhere('client_name', 'LIKE',  "%$searchquery%")->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage)
					: Invoice::where('tenantID', '=',  $this->tenantID)->where('payment', '=', 1)->where('quote', '=', 0)->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage);
			}
			
			// No filter set
			if($filter == 'undefined'){									
				
				$filter_label = "";
				$total_records = Invoice::count($searchquery, $quote);
				
				$invoices = $searchquery
					? Invoice::where('tenantID', '=',  $this->tenantID)->where('quote', '=', 0)->where('tenant_'.$request_type.'_id', 'LIKE',  "%$searchquery%")->orWhere('client_name', 'LIKE',  "%$searchquery%")->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage)
					: Invoice::where('tenantID', '=',  $this->tenantID)->where('quote', '=', 0)->orderBy('tenant_'.$request_type.'_id','desc')->paginate($this->perPage);
				
			}
		 
		}

	 
		 
        return View::make('invoices.index')
		   ->with('title', $title)
		   ->with(compact('total_records'))
		   ->with('invoices', $invoices)
		   ->with('clients', Client::where('tenantID', '=', $this->tenantID)->get())		   		
		   ->with('searchquery', $searchquery)
		   ->with('request_type', $request_type)
		   ->with(compact('filter_label'))
		   ->with('preferences', Preference::where('tenantID', '=',  $this->tenantID)->first());
	}
 
	
	public function create()
	{
		// One-time setup check
		$onetimecheck = new OnetimeUpdater($this->preference, $this);
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please ensure that your currency and time zone options are set.'); }

        $request_type;

		// If it's a quote
		if(str_contains(Request::url(), 'quote'))
		{
			$request_type = 'quote';
			$title = 'Create new quote';
			// Restrictions
			if($this->totalQuotesThisMonth >= $this->monthlQuoteLimit){
				$this->limitReached = TRUE;
			}else{
				$this->limitReached = FALSE;
			}
		}
		else
		{
			$request_type = 'invoice';
			$title = 'Create new invoice';

			// Restrictions
			if($this->totalInvoicesThisMonth >= $this->monthlyInvoiceLimit){
				$this->limitReached = TRUE;
			}else{
				$this->limitReached = FALSE;
			}

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

		if($preference->business_model > 1)
		{
			return Redirect::to('dashboard')->with('failed_flash_message', 'Unknown error occured, please try again.');
		}

		$default_currency = Preference::where('tenantID', '=',  $this->tenantID)->lists('currency_code');
		$currency_rates = CurrencyRate::where('tenantID', '=', $this->tenantID)->lists('currency_code');

		$currency_list = array();
		if(count($currency_rates) > -1){
			$currency_list = array_merge($default_currency, $currency_rates);		}
		else{
			$currency_list = $default_currency;
		}


       // dd(Invoice::tenant_last_used_invoice_id());


		return View::make('invoices.create')
		->with('title', $title)
		->with('preferences', $preference)
		->with('items', Item::where('tenantID', '=',  $this->tenantID)->orderBy('item_name', 'asc')->get())
		->with('clients', Client::where('tenantID', '=',  $this->tenantID)->orderBy('company', 'asc')->get())
		->with('currencies', Currency::where('three_code', '=' , $preference->currency_code)->get())
		->with('countries', Country::all())
		->with('currency_list', $currency_list)
		->with('limit_reached', $this->limitReached)
		->with('company', Company::where('tenantID', '=',  $this->tenantID)->first())
		->with('tenant_last_invoice_id', Invoice::tenant_last_invoice_id())
		->with('tenant_last_quote_id', Invoice::tenant_last_quote_id())
		->with('tenant_last_used_invoice_id', Invoice::tenant_last_used_invoice_id())
		->with('tenant_last_used_quote_id', Invoice::tenant_last_used_quote_id())
		->with('request_type', $request_type);
	}


	public function clients_select_list()
	{
		$clients = Client::where('tenantID', '=',  $this->tenantID)->orderBy('company', 'asc')->get();
		$newest_client = Client::where('tenantID', '=',  $this->tenantID)->orderBy('created_at', 'desc')->first();
		return View::make('invoices.ajax_clients_list')
		->with('clients', $clients)
		->with('newest_client', $newest_client);
	}
	
	
	public function items_select_list()
	{
		$items = Item::where('tenantID', '=',  $this->tenantID)->orderBy('item_name', 'asc')->get();
		$newest_item = Item::where('tenantID', '=',  $this->tenantID)->orderBy('created_at', 'desc')->first();
		return View::make('invoices.ajax_items_list')
		->with('preferences', Preference::where('tenantID', '=',  $this->tenantID)->first())
		->with('items', $items)
		->with('newest_item', $newest_item);
	}

	
	public function check_invoice_id()
	{
		$tenant_invoice_id = trim(Input::get('tenant_invoice_id'));
		if(Invoice::where('tenantID', '=', $this->tenantID)->where('quote', '=', 0)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function check_quote_id()
	{
		$tenant_quote_id = trim(Input::get('tenant_quote_id'));
		if(Invoice::where('tenantID', '=', $this->tenantID)->where('quote', '=', 1)->where('tenant_quote_id', '=', $tenant_quote_id)->first()){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function invoice_attachment($id)
	{
		$tenant_invoice_id = $id;
		$input = Input::All();
        $rules = array(
              'file' => 'mimes:jpg,jpeg,png,doc,docx,xls,xlsx,pdf,ppt,txt,zip|max:5000|required'            
        );
	  
         $validation = Validator::make($input, $rules);
		 
		 if($validation->fails()){
			return Redirect::route('invoice', $id)->withErrors($validation)->withInput();
		 }else{
		 	
			// Check if attachment exists delete
			
			$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $id)->first(); 
			
			if($invoice->file != NULL && $invoice->file != ""){
				// Delete file
				$pathToFile = public_path().'/te_da/'.$this->tenantID.'/attachments/invoices/'. Apphelper::decrypt($invoice->file, $invoice->tenantID);
				 
				if(file_exists($pathToFile)){
					File::delete($pathToFile);
				}
			} 
			
		 
	        $file = Input::file('file'); // your file upload input field in the form should be named 'file'
	
	        $destinationPath = public_path().'/te_da/'.$this->tenantID.'/attachments/invoices/';	
			
			// $extenstion = $file->getClientOriginalExtension();			         
	        $filename = "file". $tenant_invoice_id. "_".Input::file('file')->getClientOriginalName();	
		 
			$encryp_name = AppHelper::encrypt($filename, $this->tenantID);
			 
	        $uploadSuccess = Input::file('file')->move($destinationPath, $filename);
			 
			if( $uploadSuccess ) {
				
				// Update Invoice with file encryp_name				
				$updateService = new Updater($this->invoice, $this);
				$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, array(			 
						'file' => $encryp_name				 
			    )); 
				 
          	 return Redirect::route('invoice', $id)
					->with('flash_message', 'File was successfully attached - see bottom of this page for file');
	        } else {
	             return Redirect::route('invoice', $id)
						->with('failed_flash_message', 'File was not uploaded.');
	        }

		 }
	}


	public function download_file()
	{
		$tenant_invoice_id = (int)Request::segment(2);
		 
		$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();  
		$file = $invoice->file;
		 
		if(!is_int($tenant_invoice_id)  || $file == NULL || $file == ""){
			return Redirect::route('invoices')->with('failed_flash_message', 'File could not be downloaded.');
		}
	 
		$pathToFile = public_path().'/te_da/'.$this->tenantID.'/attachments/invoices/'. Apphelper::decrypt($file, $invoice->tenantID);
		
		if(file_exists($pathToFile)){		
			return Response::download($pathToFile);
		}else{
			return Redirect::route('invoice', $tenant_invoice_id)->with('failed_flash_message', 'File could not be downloaded.');
		}
	}
	
	
	public function remove_file()
	{
		$tenant_invoice_id = (int)Request::segment(2);
		
		$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first(); 
			
		if($invoice->file != NULL && $invoice->file != ""){
			// Delete file
			$pathToFile = public_path().'/te_da/'.$this->tenantID.'/attachments/invoices/'. Apphelper::decrypt($invoice->file, $invoice->tenantID);
			
			if(file_exists($pathToFile)){
				File::delete($pathToFile);
			 }
			
			// Update Invoice with file encryp_name				
			$updateService = new Updater($this->invoice, $this);
			$updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, array(			 
					'file' => NULL				 
		    )); 
		} 
		
		return Redirect::route('invoice', $tenant_invoice_id)
					->with('flash_message', 'Attached file was successfully removed.');
	}
	
	 
	public function store()
	{
	 
		$new_id = (int)ltrim(trim(Input::get('inv_num')), '0');
		$request_type = trim(Input::get('request_type'));
		 
		if($request_type == 'invoice'){			 
			if(Invoice::where('tenantID', '=', $this->tenantID)->where('quote', '=', 0)->where('tenant_invoice_id', '=', $new_id)->first()){
				Session::flash('failed_flash_message', 'Invoice creation failed');
				return 0;
			} 
		}elseif($request_type == 'quote'){		 
			if(Invoice::where('tenantID', '=', $this->tenantID)->where('quote', '=', 1)->where('tenant_quote_id', '=', $new_id)->first()){
				Session::flash('failed_flash_message', 'Quote creation failed');
				return 0;
			}
		}
		 
		$client_name = trim(Input::get('client_company'));
		$invoice_subj = trim(Input::get('invoice_subj'));	
		$tenantID = $this->tenantID;
		$client_id = trim(Input::get('cl_id'));
		$items = trim(Input::get('data'));		
		$items = str_replace('__amp__', '&', $items);
		$token =  mt_rand();				
		// $items = preg_quote($items, '/');				
		if($last_invoice = Invoice::tenant_last_invoice_id()){
			$new_inv_id = (int)$last_invoice + 1; 
			$tenant_invoice_id = $new_inv_id;
			
			if($new_id != NULL)
			{
				$tenant_invoice_id = (int)$new_id;
				
				if($tenant_invoice_id == 0 || $tenant_invoice_id == NULL)
				{
					$tenant_invoice_id = $new_inv_id;
				}
			}
			 
		}
		else
		{
			if($new_id != NULL)
			{
				$tenant_invoice_id = (int)$new_id;
				
				if($tenant_invoice_id == 0 || $tenant_invoice_id == NULL)
				{
					$tenant_invoice_id = $new_id;
				}
			}
			else {
				$tenant_invoice_id = 1;
			}
		 
		}
		
		if($last_quote = Invoice::tenant_last_quote_id()){
			$new_qto_id = (int)$last_quote + 1; 
			$tenant_quote_id = $new_qto_id;
			
			if($new_id != NULL)
			{
				$tenant_quote_id = (int)$new_id;
				
				if($tenant_quote_id == 0 || $tenant_quote_id == NULL)
				{
					$tenant_quote_id = $new_inv_id;
				}
			}
		}
		else
		{
			if($new_id != NULL)
			{
				$tenant_quote_id = (int)$new_id;
				
				if($tenant_quote_id == 0 || $tenant_quote_id == NULL)
				{
					$tenant_quote_id = $new_id;
				}
			}
			else {
				$tenant_quote_id = 1;
			}
		 
		}


        $time = date('h:i:s', time());

		$user_id = trim(Input::get('user_id'));
		$currency_code = trim(Input::get('currency_code'));
		$po_number = trim(Input::get('po_number'));
		$payment = 0;		
		// $due_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('due_date')), $this->date_format). " 00:00:00";	
		$created_at = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('issue_date')), $this->date_format). " ".$time;
		$updated_at = $created_at;		
		$currency_id = trim(Input::get('cur_val'));
		$note = trim(Input::get('inv_note'));		
		$subtotal = trim(Input::get('subtotal'));
		$balance_due = trim(Input::get('balance_due'));
		$discount_val = trim(Input::get('discount_val'));
		$tax_val = trim(Input::get('tax_val'));
		$status = 0;
		$receipt = 0;
		$enable_discount = trim(Input::get('enable_discount'));
		$enable_tax = trim(Input::get('enable_tax'));
		$business_model	= trim(Input::get('business_model'));
		$bill_option = trim(Input::get('bill_option'));
		
		if($request_type == 'invoice')
		{
			$due_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('due_date')), $this->date_format).  " ".$time;
			$quote = 0;
			$tenant_quote_id = 0;
			$bankinfo = trim(Input::get('bankinfo'));
		}
		else if($request_type == 'quote')
		{
			$due_date = Carbon::now();
			$quote = 1;
			$tenant_invoice_id = 0;
			$bankinfo = 0;
		}
		
		$creatorService = new Creator($this->invoice, $this);		
		return $creatorService->create(array(
			    'client_name' => $client_name,
				'items' => $items,
				'bankinfo' => $bankinfo,
				'due_date' => $due_date,				
				'payment' => $payment,
				'purchase_order_number' => $po_number,
				'user_id' => $user_id,
				'created_at' => $created_at,
				'updated_at' => $updated_at,
				'currency_id' => $currency_id,
				'currency_code' => $currency_code,		
				'note' => $note,
				'subtotal' => $subtotal,				
				'balance_due' => $balance_due,
				'discount_val' => $discount_val,
				'tax_val' => $tax_val,				
				'client_id' => $client_id,
				'tenant_invoice_id' => $tenant_invoice_id,
				'tenant_quote_id' => $tenant_quote_id,
				'quote' => $quote,			
				'status' => $status,
				'receipt' => $receipt,
				'enable_discount' => $enable_discount,				
				'enable_tax' => $enable_tax,
				'business_model' => $business_model,
				'bill_option' => $bill_option,
				'subject' => $invoice_subj,		
				'tenantID' => $tenantID,
				'token' => $token
		)); 
	 
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($tenant_invoice_id)
	{
		// One-time setup check
		$onetimecheck = new OnetimeUpdater($this->preference, $this);
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please ensure that your currency and time zone options are set.'); }
		 
		$preferences = Preference::where('tenantID', '=',  $this->tenantID)->first();
		$company = Company::where('tenantID', '=',  $this->tenantID)->first();
		
		if(str_contains(Request::url(), 'invoice'))
		{
			$invoice = Invoice::where('tenantID', '=',  $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first(); 
		}
		else 
		{
			 $invoice = Invoice::where('tenantID', '=',  $this->tenantID)->where('tenant_quote_id', '=', $tenant_invoice_id)->first();
		}
		
		
		
		$client = Client::find($invoice->client_id);
		$currencies = Currency::all();
		
		if($preferences->business_model == 0)
		{
			 $edit_items = Item::where('tenantID', '=',  $this->tenantID)->where('item_type', '=', 'product')->get();
		}
		else if($preferences->business_model == 1)
		{
			$edit_items = Item::where('tenantID', '=',  $this->tenantID)->where('item_type', '=', 'service')->get();
		}
		
		
		
		if($preferences->business_model > 1)
		{
			return Redirect::to('dashboard')->with('failed_flash_message', 'Unknown error occured, please try again.');
		}
		
		$request_type;
		
		// If it's a quote
		if(str_contains(Request::url(), 'quote'))
		{
			$request_type = 'quote'; 
			$title = 'Edit quote'.AppHelper::invoiceId($invoice->tenant_quote_id);
		}
		else
		{
			$request_type = 'invoice';
			$title = "Edit invoice".AppHelper::invoiceId($invoice->tenant_invoice_id);
		}
		 
		 
		$default_currency = Preference::where('tenantID', '=',  $this->tenantID)->lists('currency_code');
		$currency_rates = CurrencyRate::where('tenantID', '=', $this->tenantID)->lists('currency_code');
		
		$currency_list = array();		
		if(count($currency_rates) > -1){				
			$currency_list = array_merge($default_currency, $currency_rates);		}
		else{
			$currency_list = $default_currency;
		}
		
		
		return View::make('invoices.edit')
		->with(compact($title))
		->with(compact('preferences'))
		->with(compact('edit_items'))
		->with(compact('client'))
		->with(compact('invoice'))
		->with('currency_list', $currency_list)
		->with('limit_reached', $this->limitReached)
		->with(compact('company'))
		->with('tenant_last_invoice_id', Invoice::tenant_last_invoice_id())
		->with('tenant_last_quote_id', Invoice::tenant_last_quote_id())
		->with('request_type', $request_type);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 **/

	public function update($tenant_invoice_id)
	{
		$request_type = trim(Input::get('request_type'));
		 
		if(isset($_POST['data']) && isset($_POST['tenantID'])){
		
		$tenantID = stripslashes(strip_tags(trim(Input::get('tenantID'))));
			
		$currency_code = trim(Input::get('currency_code'));
		$inv_id = stripslashes(strip_tags(trim(Input::get('inv_id'))));	
	 
		$invoice_subj = trim(Input::get('invoice_subj'));
		$items = trim(Input::get('data'));		
		$items = str_replace('__amp__', '&', $items);
		//$created_at = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('issue_date')), $this->date_format). " 00:00:00";
		//$due_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('due_date')), $this->date_format). " 00:00:00";
		$updated_at = strftime("%Y-%m-%d %H:%M:%S", time());
		$user_id = trim(Input::get('user_id'));
		$po_number = trim(Input::get('purchase_order_number'));
		$currency_id = trim(Input::get('cur_val'));
		$note = trim(Input::get('inv_note'));
		
		$enable_discount = trim(Input::get('enable_discount'));
		$enable_tax = trim(Input::get('enable_tax'));
		$business_model	= trim(Input::get('business_model'));
		$bill_option = trim(Input::get('bill_option'));
		
		$subtotal = trim(Input::get('subtotal'));
		$balance_due = trim(Input::get('balance_due'));
		$discount_val = trim(Input::get('discount_val'));
		$tax_val = trim(Input::get('tax_val'));

        $time = date('h:i:s', time());
		
		if($request_type == 'invoice')
		{
			$due_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('due_date')), $this->date_format). " ".$time;
			$created_at = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('issue_date')), $this->date_format).  " ".$time;
			$bankinfo = trim(Input::get('bankinfo'));		 
		}
		else if($request_type == 'quote')
		{	 		 
			$tenant_quote_id = $tenant_invoice_id;
			$due_date = Carbon::now();
			$created_at = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('issue_date')), $this->date_format). " ".$time;
			$bankinfo = 0;
		}
		 
		
			$updateService = new Updater($this->invoice, $this);
				
			if($request_type == 'invoice')
			{
				return $updateService->update($tenantID, $tenant_invoice_id, array(			 
					'items' => $items,
					'bankinfo' => $bankinfo,
					'due_date' => $due_date,				
					'purchase_order_number' => $po_number,
					'user_id' => $user_id,
					'created_at' => $created_at,
					'updated_at' => $updated_at,
					'currency_id' => $currency_id,
					'currency_code' => $currency_code,		
					'note' => $note,
					'subtotal' => $subtotal,				
					'balance_due' => $balance_due,
					'discount_val' => $discount_val,
					'tax_val' => $tax_val,
					'tenant_quote_id' => 0,
					'quote' => 0,			
					'enable_discount' => $enable_discount,				
					'enable_tax' => $enable_tax,
					'business_model' => $business_model,
					'bill_option' => $bill_option,
					'subject' => $invoice_subj,		
					
					)); 
				
			}
			else if($request_type == 'quote')
			{
				 
				return $updateService->update_quote($tenantID, $tenant_quote_id, array(			 
					'items' => $items,
					'bankinfo' => $bankinfo,
					'due_date' => $due_date,				
					'purchase_order_number' => $po_number,
					'user_id' => $user_id,
					'created_at' => $created_at,
					'updated_at' => $updated_at,
					'currency_id' => $currency_id,
					'currency_code' => $currency_code,		
					'note' => $note,
					'subtotal' => $subtotal,				
					'balance_due' => $balance_due,
					'discount_val' => $discount_val,
					'tax_val' => $tax_val,				
					'tenant_invoice_id' => 0,
					'tenant_quote_id' => $tenant_quote_id,
					'quote' => 1,			
					'enable_discount' => $enable_discount,				
					'enable_tax' => $enable_tax,
					'business_model' => $business_model,
					'bill_option' => $bill_option,
					'subject' => $invoice_subj			 
					)); 
			
				 
			}

			// if($this->auto_download($tenant_id, $inv_id)){
				// redirect('/invoices');
			// }
			
		
		// Load show view
		}else{
			
			// Not post items
			if($request_type == 'invoice')
			{
				redirect('/invoices', 'refresh');
				
			}else if($request_type == 'quote'){
			
				redirect('/quotes', 'refresh');
			}
			
			
		}
	}

	
	
	public function copy($tenant_invoice_id){
		
		// Access Control
		// One-time setup check
		$onetimecheck = new OnetimeUpdater($this->preference, $this);
		if(!$onetimecheck->verify()){  return Redirect::route('onetime')->with('failed_flash_message', 'Please ensure that your currency and time zone options are set.'); }
		
       // $copy_script;
		// Restrictions
		if($this->totalInvoicesThisMonth >= $this->monthlyInvoiceLimit){
			$this->limitReached = TRUE;		   
		    //$copy_script = "";			
		}else{
			$this->limitReached = FALSE;
			//$copy_script = ",copy_invoice";
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
		
		if($preference->business_model > 1)
		{
			return Redirect::to('dashboard')->with('failed_flash_message', 'Unknown error occured, please try again.');
		}
		
		$request_type;
		
		// If it's a quote
		if(str_contains(Request::url(), 'quote'))
		{
			$request_type = 'quote'; 
			$title = 'Create new quote';
			$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_quote_id', '=', $tenant_invoice_id)->first();
		}
		else
		{
			$request_type = 'invoice';
			$title = 'Create new invoice';
			$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
			
		}
		
		// Currency
		$default_currency = Preference::where('tenantID', '=',  $this->tenantID)->lists('currency_code');
		$currency_rates = CurrencyRate::where('tenantID', '=', $this->tenantID)->lists('currency_code');
		
		$currency_list = array();		
		if(count($currency_rates) > -1){				
			$currency_list = array_merge($default_currency, $currency_rates);		}
		else{
			$currency_list = $default_currency;
		}
	 
		return View::make('invoices.copy')
		->with('title', $title)
		->with('preferences', $preference)
		->with('edit_items', Item::where('tenantID', '=',  $this->tenantID)->get())
		->with('clients', Client::where('tenantID', '=',  $this->tenantID)->orderBy('company', 'asc')->get())
		->with('invoice', $invoice)
		->with('currency_list', $currency_list)
		->with('limit_reached', $this->limitReached)
		->with('countries', Country::all())
		->with('company', Company::where('tenantID', '=',  $this->tenantID)->first())
		->with('tenant_last_invoice_id', Invoice::tenant_last_invoice_id())
		->with('tenant_last_quote_id', Invoice::tenant_last_quote_id())
		->with('tenant_last_used_invoice_id', Invoice::tenant_last_used_invoice_id())
		->with('tenant_last_used_quote_id', Invoice::tenant_last_used_quote_id())
		->with('request_type', $request_type);
	 	 
	} // End Copy
	
	

	public function invoiceCreationFails(){
		
		return Session::flash('failed_flash_message', 'Invoice creation failed');
 	
	}
	
	public function invoiceCreationSucceeds($invoice){
		
		if($invoice->quote == 0){
			Session::flash('flash_message', 'Invoice was successfully created.');
			return $invoice->tenant_invoice_id;
		     
		}else if($invoice->quote == 1){
			Session::flash('flash_message', 'Quote was successfully created.'); 
			return $invoice->tenant_quote_id;		 
		}
	  
	}
	
	
	public function convert_to_invoice($id)
	{
		
	    $last_invoice = Invoice::tenant_last_invoice_id();
		$new_inv_id = (int)$last_invoice + 1; 
		$tenant_invoice_id = $new_inv_id;		 
	 
		$converterService = new QuoteConverter($this->invoice, $this);		
		if($converterService->convert((int)$id, array(
			    'tenant_quote_id' => 0,
			    'tenant_invoice_id' => $tenant_invoice_id,
			    'quote' => 0,
			    'updated_at' => Carbon::now()		  
		)))
		{
			return Redirect::to('invoices')->with('flash_message', 'New invoice was successfully created from quote.');
		}
		else 
		{
			return Redirect::to('quotes/'.$id)->with('failed_flash_message', 'An error occured while converting the quote.');
		}
		
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{ 
		
		if(is_null($id))
		{
			return Redirect::to('invoices')->with('failed_flash_message', 'Invalid Invoice number');
		}
		
		
		// $quote = Invoice::where('tenantID', '=', Session::get('tenantID'))->where('tenant_quote_id', '=', $id)->first();
		 
		// If it's a quote
		if(str_contains(Request::url(), 'quote'))
		{
			$invoice_or_quote = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_quote_id', '=', $id)->first();
			
			$request_type = 'quote'; 
			$title = 'Quote '.AppHelper::quoteId($invoice_or_quote->tenant_quote_id);
			$client = Client::where('tenantID', '=', $this->tenantID)->where('id', '=', $invoice_or_quote->client_id)->first();	
			if(is_null($invoice_or_quote))
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Quote with ID: '. $id .' not found');
			}
		}
		else
		{
			$invoice_or_quote = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $id)->first();
			
			if(is_null($invoice_or_quote))
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Invoice with ID: '. $id .' not found');
			}
			$client = Client::where('tenantID', '=', $this->tenantID)->where('id', '=', $invoice_or_quote->client_id)->first();	
			$request_type = 'invoice';
			$title = 'Invoice '.AppHelper::invoiceId($invoice_or_quote->tenant_invoice_id);			
		}
		
		if(!$client){ return Redirect::route('invoices')->with('failed_flash_message', 'An error occured, please verify that the client exists.'); }
			 
		return View::make('invoices.show')
		->with('title', $title)
		->with('company', Company::where('tenantID', '=', $this->tenantID)->first())
		->with('preferences', Preference::where('tenantID', '=', $this->tenantID)->first())
		->with('invoice', $invoice_or_quote)
		->with('client', $client)
		->with('request_type', $request_type)
		->with('part_paid_amount', InvoicePayment::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $invoice_or_quote->tenant_invoice_id)->sum('amount'))
		->with('scripts', 'show_invoice');
	  
	}

	
	
	
	public function send($tenant_invoice_id){
		
		if(!is_null($tenant_invoice_id) && is_numeric($tenant_invoice_id)){
			
				// Access Control
				if($this->subscripionHistory->restrictAccess())
				{
					Redirect::to('subscriptions/history')->with('flash_message', 'Your account is restricted from sending invoices because your subscription has expired. Please renew to continue using your system as normal.');
				}
				
				
				$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
				$company = Company::where('tenantID', '=', $this->tenantID)->first();
			 
				// If it's a quote
				if(str_contains(Request::url(), 'quote'))
				{
				 
				   $invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_quote_id', '=', $tenant_invoice_id)->first();
				   $client = Client::find($invoice->client_id);
				   $public_url = Config::get('app.app_domain').'view_quote/'.md5($invoice->token).'/'.$invoice->tenantID.'/'. $tenant_invoice_id;
					 
				   return View::make('invoices.send_quote')
				   ->with('title', 'Sending quote '.Apphelper::invoiceId($invoice->tenant_quote_id))
				   ->with(compact('invoice'))
				   ->with(compact('client'))
				   ->with(compact('company'))
				   ->with(compact('public_url'))
				   ->with(compact('preferences'));
				}
				else
				{
				   	
				   $invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
				   $client = Client::find($invoice->client_id);
				   $public_url = '<a href="'.Config::get('app.app_domain').'view_invoice/'. md5($invoice->token.$invoice->token). '/'.$invoice->tenantID.'/'.$tenant_invoice_id.'/'.sha1($invoice->token).'">View online</a>';
					 
				   return View::make('invoices.send')
				   ->with('title', 'Sending invoice '.Apphelper::invoiceId($invoice->tenant_invoice_id))
				   ->with(compact('invoice'))
				   ->with(compact('client'))
				   ->with(compact('company'))
				   ->with(compact('public_url'))
				   ->with(compact('preferences')); 		
				}
				
			 
			
		}else{
			// Redirect back
			return Redirect::back();
		}
		
	} // End Send
	
	
	public function offline_send($tenant_invoice_id){
		
		 Invoice::update_status($this->tenantID, $tenant_invoice_id, 1);
	     return Redirect::to('payments/'.$tenant_invoice_id)->with('flash_message', 'Invoice has been marked as sent (offline)');
		
	}
	
	
	public function email_invoice($tenant_invoice_id){
		
		$invoice = Invoice::where('tenantID', '=',  $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
		 
		$client = Client::find($invoice->client_id);
		$company = Company::where('tenantID', '=', $this->tenantID)->first();
		$public_url = Config::get('app.app_domain').'/get_invoice/show/'. $tenant_invoice_id.'/' .md5($invoice->token). '/'.$invoice->tenantID.'/';
		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		
		// Send email
		$inv_email_subject = Input::get('invoice_email_subject');
		$inv_email_body = Input::get('invoice_email_body');
		
		$inv_email_body = str_replace("\r\n","<br />",$inv_email_body); // Replaces Blank lines with <br />
	    $inv_email_body = str_replace("\n","<br />",$inv_email_body); 
		
		// Do multile options		
		$client_email = $client->email;
		 
			 
		$from_email = $company->email;
		$from_name = $company->company_name;
		 
		//$this->load->library('email');
		$this->download($tenant_invoice_id, false);
		 
		$ts = strtotime($invoice->created_at);
		$mytoday = date('Y-m-d', $ts);				
		
		//$pdf_file = underscore(convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company).'_invoice_'.invoiceId($invoice->tenant_invoice_id).'.pdf';
		$pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company.'_invoice_'.AppHelper::invoiceId($invoice->tenant_invoice_id).'.pdf';
		$attachment = public_path() . '/te_da/'.$this->tenantID.'/invoices/'.$pdf_file;
		
		$attachment = str_replace(' ', '_', $attachment);
		
		// Only Primary
		if(isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
		 
			if($this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name, $attachment))
			{			 
				 // // Update sending status
				 Invoice::update_status($this->tenantID, $tenant_invoice_id, 1);
				 return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice was sent to '.$client->company. ' : '.trim($_POST['email_primary']).'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Invoice not sent.');		 
			}
		}
		
		
		// Only Secondary
		if(isset($_POST['email_secondary']) && !isset($_POST['email_primary'])){
		 
			if($this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name, $attachment))
			{			 
				 // // Update sending status
				 Invoice::update_status($this->tenantID, $tenant_invoice_id, 1);
				 return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice was sent to '.$client->company. ' : '.trim($_POST['email_secondary']).'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Invoice not sent.');		 
			}
		}
		
		
		// If Both		 
		if(isset($_POST['email_primary']) && isset($_POST['email_secondary'])){
			
			$this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name, $attachment);
			$this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name, $attachment); 
			
			Invoice::update_status($this->tenantID, $tenant_invoice_id, 1);
			return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice was sent to '.$client->company. ' : '.trim($_POST['email_primary']).' and '.trim($_POST['email_secondary']) .'');
		}
		
		// No contact selected, send to default
		if(!isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
			
			if($this->mailer->send_invoice($inv_email_subject, $inv_email_body, $client->email, $client->firstname, $from_email, $from_name, $attachment))
			{			 
				 // // Update sending status
				 Invoice::update_status($this->tenantID, $tenant_invoice_id, 1);
				 return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice was sent to '.$client->company. ' : '.$client->email.'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Invoice not sent.');		 
			}
		}
	 
  
	}

	
	public function email_quote($tenant_quote_id){
 
		$invoice = Invoice::where('tenantID', '=',  $this->tenantID)->where('tenant_quote_id', '=', $tenant_quote_id)->first();
		 
		$client = Client::find($invoice->client_id);
		$company = Company::where('tenantID', '=', $this->tenantID)->first();
		$public_url = Config::get('app.app_domain').'/get_invoice/show/'. $tenant_quote_id.'/' .md5($invoice->token). '/'.$invoice->tenantID.'/';
		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		
		// Send email
		$inv_email_subject = Input::get('invoice_email_subject');
		$inv_email_body = Input::get('invoice_email_body');
		
		$inv_email_body = str_replace("\r\n","<br />",$inv_email_body); // Replaces Blank lines with <br />
	    $inv_email_body = str_replace("\n","<br />",$inv_email_body);  
		
		$client_email = $client->email;
	 
		$from_email = $company->email;
		$from_name = $company->company_name;
		 
		$this->download($tenant_quote_id, false);
		 
		$ts = strtotime($invoice->created_at);
		$mytoday = date('Y-m-d', $ts);				
	 
		$pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company.'_quote_'.AppHelper::invoiceId($invoice->tenant_quote_id).'.pdf';
		$attachment = public_path() . '/te_da/'.$this->tenantID.'/invoices/'.$pdf_file;
		
		$attachment = str_replace(' ', '_', $attachment);
		 
		// Only Primary
		if(isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
		 
			if($this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name, $attachment))
			{			 
				 // // Update sending status
				 Invoice::update_quote_status($this->tenantID, $tenant_quote_id, 1);
				 return Redirect::route('quote', $tenant_quote_id)->with('flash_message', 'Quote was sent to '.$client->company. ' : '.trim($_POST['email_primary']).'');
			}
			else 
			{
				return Redirect::to('quotes')->with('failed_flash_message', 'Quote not sent.');		 
			}
		}
		
		
		// Only Secondary
		if(isset($_POST['email_secondary']) && !isset($_POST['email_primary'])){
		 
			if($this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name, $attachment))
			{			 
				 // // Update sending status
				 Invoice::update_quote_status($this->tenantID, $tenant_quote_id, 1);
				 return Redirect::route('quote', $tenant_quote_id)->with('flash_message', 'Quote was sent to '.$client->company. ' : '.trim($_POST['email_secondary']).'');
			}
			else 
			{
				return Redirect::to('quotes')->with('failed_flash_message', 'Quote not sent.');		 
			}
		}
		
		
		// If Both		 
		if(isset($_POST['email_primary']) && isset($_POST['email_secondary'])){
			
			$this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name, $attachment);
			$this->mailer->send_invoice($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name, $attachment); 
			
			Invoice::update_quote_status($this->tenantID, $tenant_quote_id, 1);
			return Redirect::route('quote', $tenant_quote_id)->with('flash_message', 'Quote was sent to '.$client->company. ' : '.trim($_POST['email_primary']).' and '.trim($_POST['email_secondary']) .'');
		}
		
		// No contact selected, send to default
		if(!isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
			
			if($this->mailer->send_invoice($inv_email_subject, $inv_email_body, $client->email, $client->firstname, $from_email, $from_name, $attachment))
			{			 
				 // // Update sending status
				 Invoice::update_quote_status($this->tenantID, $tenant_quote_id, 1);
				 return Redirect::route('quote', $tenant_quote_id)->with('flash_message', 'Quote was sent to '.$client->company. ' : '.$client->email.'');
			}
			else 
			{
				return Redirect::to('quotes')->with('failed_flash_message', 'Invoice not sent.');		 
			}
		}
	 
	}


	public function export()
	{
		return View::make('invoices.export')->with('title', 'Export Invoices');
	}
 
 
	public function process_export()
	{
		
		$invs = DB::table('invoices')->join('clients', 'invoices.client_id', '=', 'clients.id')
		->where('invoices.tenantID', '=', $this->tenantID)
		->where('invoices.quote', '=', 0)
		->get(array('clients.company', 'invoices.due_date', 'invoices.currency_code', 'invoices.tenant_invoice_id', 'invoices.subtotal', 'invoices.discount_val', 'invoices.tax_val', 'invoices.balance_due'));
	  
		// Convert to array due to using Fluent which does not support toArray()
		$invs = json_decode(json_encode((array) $invs), true);		
		$invoices = array();
	 
		
		foreach($invs as $inv){
			
			$inv['tenant_invoice_id'] = '_'.AppHelper::invoiceId($inv['tenant_invoice_id']);			
			array_push($invoices, $inv);
		}
	 	
		$tax_total = DB::table('invoices')->where('invoices.tenantID', '=', $this->tenantID)->sum('tax_val');
		$balance_due = DB::table('invoices')->where('invoices.tenantID', '=', $this->tenantID)->sum('balance_due');
		
	 
		$filepath = public_path(). '/te_da/'.$this->tenantID.'/user_data/'.'invoices.csv';
	    $file = fopen($filepath, 'w');
		
		$header = array('Client', 'Due Date', 'Currency code', 'Invoice No', 'Net Amount', 'Discount', 'Tax', 'Total Amount');
		
		//$blank = array(' ', ' ', ' ', ' ', ' ', ' ', ' ', '');
		//$total_header = array(' ', ' ', ' ', ' ', ' ', ' ', 'Total Tax ', 'NET Total');		
		//$total_values = array(' ', ' ', ' ', ' ', ' ', ' ', $tax_total, $balance_due);
		 
		fputcsv($file, $header);
		
	    foreach ($invoices as $invoice) {
	        fputcsv($file, $invoice);
	    }
		
		//fputcsv($file, $blank);
		//fputcsv($file, $blank);
		//fputcsv($file, $total_header);
		//fputcsv($file, $total_values);
	 
	    fclose($file);
		 
		// Return excel format
		// return Excel::load($filepath)->convert('xls');
		
  		// Return CSV format
	    return Response::download($filepath);
	}


 
	public function mark_sent(){
		$update_data = array();
		if(!is_null($this->uri->segment(3)) && is_numeric($this->uri->segment(3))){
			
			$update_data = array(		
				'status' => 1,
				'tenant_invoice_id' => $this->uri->segment(3),							
				'tenantID' => $this->tenantID
		    );
			
			$this->Invoice_model->markas_sent($update_data, $this->uri->segment(3), $this->tenantID);		
			$this->session->set_flashdata('operation_success', '<p class="messageboxok">Invoice '.invoiceId($this->uri->segment(3)).' was maked as sent</p>');	
			redirect('/payments/history/'.invoiceId($this->uri->segment(3)));
			
		}else{
			$this->showall(0);
		}
	}
	
	
	public function reminder($tenant_invoice_id){
		
		if(!is_null($tenant_invoice_id) && is_numeric($tenant_invoice_id)){
		 
				   
		   $preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		   $company = Company::where('tenantID', '=', $this->tenantID)->first();			 
				 
		   $invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
		   		   
		   // $this_payment = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('id', '=', $payment_id)->first();
		   $total_paid_todate = InvoicePayment::where('tenantID', '=', $invoice->tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount');
		   
		   $client = Client::find($invoice->client_id);
		   $public_url = '<a href="'.Config::get('app.app_domain').'view_invoice/'. md5($invoice->token.$invoice->token). '/'.$invoice->tenantID.'/'.$tenant_invoice_id.'/'.sha1($invoice->token).'">View online</a>';
			 
		   return View::make('invoices.reminder')
		   ->with('title', 'Sending reminder for Invoice '.Apphelper::invoiceId($invoice->tenant_invoice_id))
		   ->with(compact('invoice'))
		   ->with(compact('client'))
		   ->with(compact('company'))
		   ->with(compact('total_paid_todate'))
		   ->with(compact('public_url')) 
		   ->with(compact('preferences'));				 
			
		}else{
			// Redirect back
			return Redirect::back();
		}
		
	} // End Send
	
	
	
	public function send_reminder($tenant_invoice_id){
		
		$invoice = Invoice::where('tenantID', '=',  $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first();
		 
		$client = Client::find($invoice->client_id);
		$company = Company::where('tenantID', '=', $this->tenantID)->first();
		$public_url = Config::get('app.app_domain').'get_invoice/show/'. $tenant_invoice_id.'/' .md5($invoice->token). '/'.$invoice->tenantID.'/';
		$preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		
		// Send email
		$inv_email_subject = Input::get('reminder_email_subject');
		$inv_email_body = Input::get('reminder_email_body');
		
		$inv_email_body = str_replace("\r\n","<br />",$inv_email_body); // Replaces Blank lines with <br />
	    $inv_email_body = str_replace("\n","<br />",$inv_email_body); 
		
		// Do multile options		
		$client_email = $client->email;		 
			 
		$from_email = $company->email;
		$from_name = $company->company_name;		 
	 
		$this->download($tenant_invoice_id, false);
		 
		$ts = strtotime($invoice->created_at);
		$mytoday = date('Y-m-d', $ts); 		
		 
		
		//$pdf_file = underscore(convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company).'_invoice_'.invoiceId($invoice->tenant_invoice_id).'.pdf';
		$pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company.'_invoice_'.AppHelper::invoiceId($invoice->tenant_invoice_id).'.pdf';
		$attachment = public_path() . '/te_da/'.$this->tenantID.'/invoices/'.$pdf_file;
		
		$attachment = str_replace(' ', '_', $attachment);
		
		// Only Primary
		if(isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
		 
			if($this->mailer->send_invoice_reminder($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name, $attachment))
			{			 
				 return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice payment reminder sent to '.$client->company. ' : '.trim($_POST['email_primary']).'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Invoice payment not sent.');		 
			}
		}
		
		
		// Only Secondary
		if(isset($_POST['email_secondary']) && !isset($_POST['email_primary'])){
		 
			if($this->mailer->send_invoice_reminder($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name, $attachment))
			{	
				 return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice payment reminder sent to '.$client->company. ' : '.trim($_POST['email_secondary']).'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Invoice payment not sent.');		 
			}
		}
		
		
		// If Both		 
		if(isset($_POST['email_primary']) && isset($_POST['email_secondary'])){
			
			$this->mailer->send_invoice_reminder($inv_email_subject, $inv_email_body, trim($_POST['email_primary']), trim($_POST['firstname_primary']), $from_email, $from_name, $attachment);
			$this->mailer->send_invoice_reminder($inv_email_subject, $inv_email_body, trim($_POST['email_secondary']), trim($_POST['firstname_secondary']), $from_email, $from_name, $attachment);			
			 
			return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice payment reminder sent to '.$client->company. ' : '.trim($_POST['email_primary']).' and '.trim($_POST['email_secondary']) .'');
		}
		
		// No contact selected, send to default
		if(!isset($_POST['email_primary']) && !isset($_POST['email_secondary'])){
			
			if($this->mailer->send_invoice_reminder($inv_email_subject, $inv_email_body, $client->email, $client->firstname, $from_email, $from_name, $attachment))
			{				  
				 return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Invoice payment reminder sent to '.$client->company. ' : '.$client->email.'');
			}
			else 
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Invoice payment not sent.');		 
			}
		}

	}
	
	 
	
	
	public function download($id, $download_mode=true)
	{
		if(is_null($id))
		{
			return Redirect::to('invoices')->with('failed_flash_message', 'Failed to download - Invalid Invoice number');
		}
		
		if(str_contains(Request::url(), 'quote'))
		{
			$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_quote_id', '=', $id)->first(); 
			if(is_null($invoice))
			{
				return Redirect::to('quotes')->with('failed_flash_message', 'Failed to download - Quote with ID: '. $id .' not found');
			}
		}
		else
		{
			$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $id)->first();
			if(is_null($invoice))
			{
				return Redirect::to('invoices')->with('failed_flash_message', 'Failed to download - Invoice with ID: '. $id .' not found');
			}
		}

 
		$client = Client::where('tenantID', '=', $this->tenantID)->where('id', '=', $invoice->client_id)->first();		
		if(!$client){ return Redirect::route('invoices')->with('failed_flash_message', 'An error occurred, please verify that the client exists.'); }
	 
		
	    $preferences = Preference::where('tenantID', '=', $this->tenantID)->first();
		 
		$pdf = new Pdf();
		$pdf->setBinary(Config::get('app.binary_dir').Config::get('app.binary'));
		 

		$ts = strtotime($invoice->created_at);
	 
		$mytoday = date('Y-m-d', $ts);	
		 
		if(str_contains(Request::url(), 'quote'))
		{
			$pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company.'_quote_'.AppHelper::invoiceId($invoice->tenant_quote_id).'.pdf';
		}	 
		else
		{
			$pdf_file = AppHelper::convert_to_ddmmyyyy($mytoday, $this->date_format).'_'.$client->company.'_invoice_'.AppHelper::invoiceId($invoice->tenant_invoice_id).'.pdf'; 
		}

 		//TODO: directory creation is failing perhaps because of unix flimit ? or chmod issue
 		// Limit creting directory, instead prefix or sufix userid to invoices
 		
		$pdf_file_loc = public_path(). '/te_da/'.$this->tenantID.'/invoices/'.$pdf_file;
		
		//$pdf_file_loc = public_path(). '/te_da/'.$pdf_file;
		
		$pdf_file_loc = str_replace(' ', '_', $pdf_file_loc);
		
		//dd($pdf_file_loc);
	   
	   // If it's a quote
		if(str_contains(Request::url(), 'quote'))
		{
			
			$data = array(
	   				'title'         => 'Quote '.AppHelper::invoiceId($invoice->tenant_quote_id),
	   				'company'       => Company::where('tenantID', '=', $this->tenantID)->first(),
	   				'preferences'   => Preference::where('tenantID', '=', $this->tenantID)->first(),
	   				'invoice'       => $invoice,
	   				'client'        => $client,   				 
				); 
				
				// return View::make('quotes.download'.$preferences->invoice_template, $data);
				
				if($download_mode == false){
			   	 
				 	return $pdf->generateFromHtml(View::make('quotes.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);
					
			   	}else{
			   	 
				  $pdf->generateFromHtml(View::make('quotes.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);				  
				  return Response::download($pdf_file_loc);
			 
			  	}
				
			 
		}
		else
		{
			
			   $data = array(
	   				'title'         => 'Invoice '.AppHelper::invoiceId($invoice->tenant_invoice_id),
	   				'company'       => Company::where('tenantID', '=', $this->tenantID)->first(),
	   				'preferences'   => Preference::where('tenantID', '=', $this->tenantID)->first(),
	   				'invoice'       => $invoice,
	   				'client'        => $client,
	   				'part_paid_amount' => InvoicePayment::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $invoice->tenant_invoice_id)->sum('amount')  				 
				); 
				
				// Test Template				
			    // return View::make('invoices.download'.$preferences->invoice_template, $data);
		
				if($download_mode == false){
			   	 
				 	return $pdf->generateFromHtml(View::make('invoices.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);
					
			   	}else{
			   	 
				  $pdf->generateFromHtml(View::make('invoices.download'.$preferences->invoice_template, $data), $pdf_file_loc, array(), true);				  
				  return Response::download($pdf_file_loc);
			 
			  	}
		  
		}
	        
		
	}
	
	public function enable_discount()
	{ 
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update(array(
			'enable_discount' => 1,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
	}
	
	public function disable_discount()
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update(array(
			'enable_discount' => 0,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
	}
	
	public function enable_tax()
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update(array(
			'enable_tax' => 1,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
	}
	
	public function disable_tax()
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update(array(
			'enable_tax' => 0,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
	}



	public function enable_discount_edit($tenant_invoice_id)
	{ 
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect($tenant_invoice_id, array(
			'enable_discount' => 1,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
	}
	
	public function disable_discount_edit($tenant_invoice_id)
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect($tenant_invoice_id, array(
			'enable_discount' => 0,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
	}
	
	public function enable_tax_edit($tenant_invoice_id)
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect($tenant_invoice_id, array(
			'enable_tax' => 1,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
	}
	
	public function disable_tax_edit($tenant_invoice_id)
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect($tenant_invoice_id, array(
			'enable_tax' => 0,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
	}
	
	
	
	
	public function enable_discount_copy($tenant_invoice_id)
	{ 
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect_copy($tenant_invoice_id, array(
			'enable_discount' => 1,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
	}
	
	public function disable_discount_copy($tenant_invoice_id)
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect_copy($tenant_invoice_id, array(
			'enable_discount' => 0,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
		
	}
	
	public function enable_tax_copy($tenant_invoice_id)
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect_copy($tenant_invoice_id, array(
			'enable_tax' => 1,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
	}
	
	public function disable_tax_copy($tenant_invoice_id)
	{
		$updateService = new DiscountUpdater($this->preference, $this);	
		return $updateService->update_with_redirect_copy($tenant_invoice_id, array(
			'enable_tax' => 0,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));
	}
	
	
	
	public function discountUpdateSucceeds()
	{
		if(str_contains(Request::url(), 'invoice'))
		{
			return Redirect::to('invoices/create');
		}
		else
		{
			return Redirect::to('quotes/create');
		}
	}
	
	public function taxUpdateSucceeds()
	{
		if(str_contains(Request::url(), 'invoice'))
		{
			return Redirect::to('invoices/create');
		}
		else
		{
			return Redirect::to('quotes/create');
		}
	}
	
	public function discountUpdateFails()
	{
		if(str_contains(Request::url(), 'invoice'))
		{
			return Redirect::to('invoices/create');
		}
		else
		{
			return Redirect::to('quotes/create');
		}
	}
	
	public function taxUpdateFails()
	{
		if(str_contains(Request::url(), 'invoice'))
		{
			return Redirect::to('invoices/create');
		}
		else
		{
			return Redirect::to('quotes/create');
		}
	}
	
	
	public function invoiceUpdateSucceeds($tenant_invoice_id)
	{
		 Session::flash('flash_message', 'Updated successfully');
	}
	
	public function quoteUpdateSucceeds($tenant_quote_id)
	{
		 Session::flash('flash_message', 'Updated successfully');
	}
	
	public function invoiceUpdateFails($tenant_invoice_id)
	{
		
	}
	
	 
	public function destroy($tenenat_invoice_id)
	{
		if(str_contains(Request::url(), 'invoice'))
		{
			$removerService = new Remover($this->invoice, $this);
			
			$tenenat_invoice_id = (int)$tenenat_invoice_id;
		
			$invoice = Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenenat_invoice_id)->first(); 
			 
			if($invoice->file != NULL && $invoice->file != ""){
				// Delete file
				$pathToFile = public_path().'/te_da/'.$this->tenantID.'/attachments/invoices/'. Apphelper::decrypt($invoice->file, $invoice->tenantID);
				
				if(file_exists($pathToFile)){
					File::delete($pathToFile);
				 } 
			} 
				
			return $removerService->remove($tenenat_invoice_id);
		}
		elseif(str_contains(Request::url(), 'quote'))
		{
			$removerService = new Remover($this->invoice, $this);		
			return $removerService->removeQuote($tenenat_invoice_id);
		}
 
	}
	
	public function invoiceDeletionFails(){
		
		return Redirect::route('invoices')
					->with('failed_flash_message', 'Invoice was not deleted');
	}
	
	public function invoiceDeletionSucceeds(){
		
		return Redirect::route('invoices')
					->with('flash_message', 'Invoice was deleted successfully');
	}


	public function quoteDeletionFails(){
		
		return Redirect::route('quotes')
					->with('failed_flash_message', 'Quote was not deleted');
	}
	
	public function quoteDeletionSucceeds(){
		
		return Redirect::route('quotes')
					->with('flash_message', 'Quote was deleted successfully');
	}

    //////////////////////////////////////////////////////////////////////
    ////////// RECURRING  //////////////////////////////////

    public function invoice_recurring($tenant_invoice_id){

        $tenant_invoice_id = $tenant_invoice_id;

        if($tenant_invoice_id == null || $tenant_invoice_id == ""){
            return Redirect::back()->with('failed_flash_message', 'Invalid Invoice ID');
        }

        // Check that invoice ID exists
        if(Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first() == null){
            return Redirect::back()->with('failed_flash_message', 'Invalid Invoice ID');
        }

        $recur_schedule = trim(Input::get('recur_schedule'));
        $next_recurring_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('next_recurring_date')), $this->date_format);
        $last_recurring_date = AppHelper::convert_to_mysql_yyyymmdd(trim(Input::get('last_recurring_date')), $this->date_format);
        $recur_due_date_interval = trim(Input::get('recur_due_date_interval'));
        $recur_active_status = 0;
        $auto_send = 0;

        // Validation

        // Frequency of recurring
        if($recur_schedule == null || $recur_schedule == ""){
            return Redirect::route('invoice', $tenant_invoice_id)->with('failed_flash_message', 'Recurring frequency not set');
        }

        // next recurring date
        if($next_recurring_date == null || $next_recurring_date == ""){
            return Redirect::route('invoice', $tenant_invoice_id)->with('failed_flash_message', 'Next recurring date not set');
        }

        // Last recurring date
        if($last_recurring_date == null || $last_recurring_date == ""){
            return Redirect::route('invoice', $tenant_invoice_id)->with('failed_flash_message', 'Last recurring date not set');
        }

        if(Input::get('recur_status')){
            $recur_active_status = 1;
        }else{
            $recur_active_status = 0;
        }

        if(Input::get('auto_send')){
            $auto_send = 1;
        }else{
            $auto_send = 0;
        }

        $updateService = new Updater($this->invoice, $this);
        $updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, array(
            'recurring' => 1,
            'recur_schedule' => $recur_schedule,
            'recurring_start_date' => $next_recurring_date,
            'recur_next_date' => $next_recurring_date,
            'recurring_end_date' => $last_recurring_date,
            'recur_due_date_interval' => $recur_due_date_interval,
            'recur_status' => $recur_active_status,
            'auto_send' => $auto_send
        ));

        return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Recurring options was successfully set on this invoice.');
    }

    public function remove_recurring($tenant_invoice_id){
        if($tenant_invoice_id == null || $tenant_invoice_id == ""){
            return Redirect::back()->with('failed_flash_message', 'Invalid Invoice ID');
        }

        // Check that invoice ID exists
        if(Invoice::where('tenantID', '=', $this->tenantID)->where('tenant_invoice_id', '=', $tenant_invoice_id)->first() == null){
            return Redirect::back()->with('failed_flash_message', 'Invalid Invoice ID');
        }

        $updateService = new Updater($this->invoice, $this);
        $updateService->update_no_redirect($this->tenantID, $tenant_invoice_id, array(
            'recurring' => 0,
            'recur_schedule' => NULL,
            'recurring_start_date' => NULL,
            'recur_next_date' => NULL,
            'recurring_end_date' => NULL,
            'recur_due_date_interval' => NULL,
            'recur_status' => 0
        ));

        return Redirect::route('invoice', $tenant_invoice_id)->with('flash_message', 'Recurring was deleted on this invoice.');
    }

}