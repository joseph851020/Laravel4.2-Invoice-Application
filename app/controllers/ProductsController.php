<?php

use IntegrityInvoice\Repositories\ItemRepositoryInterface;
use IntegrityInvoice\Repositories\PaymentsHistoryRepositoryInterface;
use IntegrityInvoice\Services\Item\Creator;
use IntegrityInvoice\Services\Item\Reader;
use IntegrityInvoice\Services\Item\Updater;
use IntegrityInvoice\Services\Item\Remover;
use IntegrityInvoice\Services\Item\BulkRemover;
use Carbon\Carbon;

class ProductsController extends BaseController {
	
	public $tenantID;
	public $userId;
	public $perPage;
	public $item;
	public $itemType;
	public $accountPlan;
	public $itemLimit;
	public $tenantVerification;	
	public $totalRecords;
	public $limitReached;
	public $searchquery;
	public $subscripionHistory;
	
	
	
	public function __construct(ItemRepositoryInterface $item, PaymentsHistoryRepositoryInterface $subscripionHistory)
    {
    	$this->item = $item;
		$this->itemType = 'product';
    	$this->tenantID = Session::get('tenantID');
		$this->subscripionHistory = $subscripionHistory;
		$this->totalRecords = Item::count($this->searchquery, $this->itemType);
		$this->userId = Session::get('user_id');
		$this->perPage = Preference::where('tenantID', '=', $this->tenantID)->pluck('page_record_number');
		$this->accountPlan = Tenant::where('tenantID', '=', $this->tenantID)->pluck('account_plan_id');		 
		$this->itemLimit = AccountPlan::where('id', '=', Tenant::where('account_plan_id', '=', $this->accountPlan)->pluck('account_plan_id'))->pluck('item_limit');		
		$this->tenantVerification = Tenant::where('tenantID', '=', $this->tenantID)->pluck('verified');
 
    }
	

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->searchquery = trim(Request::get('q'));	
		
		// Redirect to dashboard if Registration has not been verified!
		// if($this->tenantVerification == 0){		 
			// return Redirect::route('dashboard')->with('failed_flash_message', 'Please check your email and verify your account');
		// }
		
		// Pass in Item Model implementation and this class	
		$readerService = new Reader($this->item, $this, $this->itemType);
		$products = $readerService->readAll($this->searchquery);
		
		return View::make('products.index')
		       ->with('title', 'List of products')
			   ->with(compact('products'))
			   ->with('totalRecords', $this->totalRecords)
			   ->with('searchquery', $this->searchquery)
			   ->with('preferences', Preference::where('tenantID', '=', $this->tenantID)->first());
	}
	
	public function import()
	{
		return View::make('products.import')
			->with('title', 'Import products');
	}
	
	public function processImport()
	{
		// SET 
		ini_set("auto_detect_line_endings", "1");
	
		$item_names = Item::where('tenantID', '=', $this->tenantID)->lists('item_name');
		
		
		$file = Input::file('itemscsv');
		 
		 	
		// Extention Validation
		if(is_null($file))
		{ 
			return Redirect::route('importProducts')->with('failed_flash_message', 'Please select a CSV file'); 
		}
  
	     if($file->getClientOriginalExtension() == "csv")
	     {
	  
	         $handle = fopen($file, "r");
	   
	   		 $row_count = 0;
	         while (($data = fgetcsv($handle, 1000, ",","\n")) !== FALSE)
	         {
	         	// Check if items is in the right format for item
	         	if($row_count == 0){
	         		
					if(count($data) != 2){
						
						return Redirect::route('importProducts')->with('failed_flash_message', 'File is not in the correct format, please check.'); 
	         			
	         		}
					
	         		if(trim($data[0]) != "Service" && trim($data[1]) != "Price"){
	         			
						return Redirect::route('importProducts')->with('failed_flash_message', 'File is not in the correct format, please check.'); 
	         		}
	         	}//		         	
	         	
	         	$row_count ++;		 
			 
				if($row_count > 1)
				{
					// If the Item name does not exit already, this is not case sensitive
					if(!in_array($data[0], $item_names)){
						
						$creatorService = new Creator($this->item, $this);
		 
						$myprice = str_replace('&', '', $data[1]);
						$myprice = str_replace('$', '', $data[1]);
						$myprice = str_replace('£', '', $data[1]);
						$myprice = str_replace('%', '', $data[1]);
						$myprice = str_replace('*', '', $data[1]);
						$myprice = str_replace('€', '', $data[1]);
						$myprice = str_replace('¥', '', $data[1]);
						$myprice = str_replace('₦', '', $data[1]);	
					    
						$temname = trim(preg_replace('/[\"]+/', '"', $data[0]),'"');						 				 
						
					    $creatorService->create(array(
							'item_name' => $temname,
							'item_type' => 'product',
							'unit_price' => $myprice,				 						
							'created_at' => Carbon::now(),
							'updated_at' => Carbon::now(),
							'user_id' => $this->userId,
							'tenantID' => $this->tenantID
						), FALSE);
 
		     
					} 
					
				} 

	         }

	         fclose($handle);
			 
			return Redirect::route('products')->with('flash_message', 'Successfully imported products.');
	     }
	     else
	     {
	     	return Redirect::route('import')->with('failed_flash_message', 'Only a file of .csv extension is allowed');
		 }   
 
	}

	public function json_list()
	{
		return Response::json(Item::where('tenantID', '=', $this->tenantID)->where('item_type', '=', 'product')->get(array('id', 'item_name', 'unit_price', 'tax_type')));
	}	

	public function export()
	{
		return View::make('products.export')->with('title', 'Export Products');
	}
	
	public function process_export()
	{
		
		// SET 
	   // ini_set("auto_detect_line_endings", "1");
    
        $products = Item::where('tenantID', '=', $this->tenantID)->where('item_type', '=', 'product')->get(array('item_name', 'unit_price'));
		   
		//dd($products);
	  
	    //$products = Item::all();
		$filepath = public_path(). '/te_da/'.$this->tenantID.'/user_data/'.'products.csv';
	    $file = fopen($filepath, 'w');
		
		$header = array('Product name', 'Price');
		
		fputcsv($file, $header);
	    foreach ($products as $product) {
	        fputcsv($file, $product->toArray());
	    }
	    fclose($file);
  
		// Return excel format
		// return Excel::load($filepath)->convert('xls');
  		// Return CSV format
		 return Response::download($filepath);
	    
	}
	
	

 

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        
		// Restrictions
		if($this->totalRecords >= $this->itemLimit){
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
		
		return View::make('products.create')
		->with('preferences', Preference::where('tenantID', '=', $this->tenantID)->first())
		->with('limitReached', $this->limitReached)
		->with('title', 'Add new product');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		
		$creatorService = new Creator($this->item, $this);
		
		// Event::fire('item.create');		
		$myprice = str_replace('', '&', Input::get('unit_price'));
		$myprice = str_replace('', '$', Input::get('unit_price'));
		$myprice = str_replace('', '£', Input::get('unit_price'));
		$myprice = str_replace('', '%', Input::get('unit_price'));
		$myprice = str_replace('', '*', Input::get('unit_price'));
		$myprice = str_replace('', '€', Input::get('unit_price'));
		
		return $creatorService->create(array(
			'item_name' =>Input::get('item_name'),
			'item_type' =>Input::get('item_type'),
			'tax_type' =>Input::get('tax_type'),
			'unit_price' => $myprice,
			'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
			'user_id' => $this->userId,
			'tenantID' =>Session::get('tenantID')
		));
		
	}

	public function itemCreationFails($errors){
		
		return Redirect::route('create_product')->withErrors($errors)->withInput();
	}
	
	public function itemCreationSucceeds(){
		
		return Redirect::route('products')
					->with('flash_message', 'New product was created successfully');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$itemReader = new Reader($this->item, $this, $this->itemType);
		$product = $itemReader->read($id);
		
        return View::make('products.show')->with(compact('product'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$product = Item::where('tenantID', '=', $this->tenantID)->where('id', '=', $id)->first();
		if(!$product){
			return Redirect::route('products')->with('failed_flash_message', 'Invalid Product ID.');
		}
		
		return View::make('products.edit')
		->with('title', 'Edit product')
		->with('preferences', Preference::where('tenantID', '=', $this->tenantID)->first())
		->with('product', $product);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		$id = Input::get('id');		
		$updateService = new Updater($this->item, $this);
		
		// Event::fire('item.create');		
		$myprice = str_replace('', '&', Input::get('unit_price'));
		$myprice = str_replace('', '$', Input::get('unit_price'));
		$myprice = str_replace('', '£', Input::get('unit_price'));
		$myprice = str_replace('', '%', Input::get('unit_price'));
		$myprice = str_replace('', '*', Input::get('unit_price'));
		$myprice = str_replace('', '€', Input::get('unit_price'));
		$myprice = str_replace(',', '', Input::get('unit_price'));

		return $updateService->update($id, array(
			'item_name' =>Input::get('item_name'),
			'item_type' =>Input::get('item_type'),
			'tax_type' =>Input::get('tax_type'),
			'unit_price' => $myprice,
			'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time())
		));

	}

	public function itemUpdateFails($id, $errors){
		
		return Redirect::route('edit_product', $id)->withErrors($errors)->withInput();
	}
	
	public function itemUpdateSucceeds($id){
		
		return Redirect::route('edit_product', $id)
					->with('flash_message', 'Updated successfully');
	}
	

	public function destroy($id)
	{
		$removerService = new Remover($this->item, $this);
		
		return $removerService->remove($id);
	}
	
	public function deletebulk(){
		
		$bulkRemoverService = new BulkRemover($this->item, $this);

		$checkboxArray  = Input::get('checkbox');

		if(!empty($checkboxArray)){
							
			return $bulkRemoverService->remove($checkboxArray);	
		}
		else
		{
			return $this->itemBulkDeletionFails();
		}

	}
	
	public function itemDeletionFails(){
		
		return Redirect::route('products')
					->with('failed_flash_message', 'Product was not deleted');
	}
	
	public function itemDeletionSucceeds(){
		
		return Redirect::route('products')
					->with('flash_message', 'Product was deleted successfully');
	}
	
	
	public function itemBulkDeletionFails(){
		
		return Redirect::route('products')
					->with('failed_flash_message', 'No product(s) was deleted');
	}
	
	public function itemBulkDeletionSucceeds(){
		
		return Redirect::route('products')
					->with('flash_message', 'The product(s) was deleted successfully');
	}

}