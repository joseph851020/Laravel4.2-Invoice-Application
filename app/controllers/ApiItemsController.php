<?php

class ApiItemsController extends BaseController {
	
   private $authUser;
   private $_params;
   private $applications;
   private $enc_request;
   private $controller;
   private $action;
   private $app_id;
    
   public function __construct()
   {
      // $this->_params = Request::all();
	  
	  //Define our id-key pairs
	  $this->applications = array(
		   'APP001' => '28e336ac6c9423d946ba02d19c6a2632', //randomly generated app key
	  );
	  
	  //get the encrypted request
   	  //$this->enc_request = Request::all();
	  $this->enc_request = Input::get('enc_request');
	  $this->controller = Input::get('controller');
	  $this->action = Input::get('action');
	  
	   //get the provided app id
   	   $this->app_id = Input::get('app_id');
	   //$this->app_id = 'APP001';
	   	  	  
	   //check first if the app id exists in the list of applications
	   if( !isset($this->applications[$this->app_id]) ) {
	      throw new Exception('Application does not exist!');
	   }
	   
	   //dd(MCRYPT_MODE_ECB);
	    // $this->enc_request = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, '28e336ac6c9423d946ba02d19c6a2632', json_encode($this->enc_request), MCRYPT_MODE_ECB));
	    
	   //decrypt the request
	   $this->_params = json_decode(trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->applications[$this->app_id], base64_decode($this->enc_request), MCRYPT_MODE_ECB)));
		
	   //check if the request is valid by checking if it's an array and looking for the controller and action
	   if( $this->_params == false || isset($this->controller) == false || isset($this->action) == false ) {
	      throw new Exception('Request is not valid');
	   }
	    
	   //cast it into an array
	   $this->_params = (array) $this->_params;
	   
	   //$action = $this->_params['action'];
	   //$this->action.'()';
	   
   }
    
   public function createItem()
   {
   	
	   // http://localhost:8000/integrityinvoice_api?controller=ApiItemsController&action=createItem&item_name=Biro&item_type=product&unit_price=23.48&tax_type=1&tenantID=bl40000001138642718660&username=nebestpal@yahoo.com&password=0000
	   //pass the user's username and password to authenticate the user   
		
	   if(Auth::attempt(array('email' => $this->_params['email'], 'password' => $this->_params['password'])))
		{
			$this->authUser = Auth::user();
			
			if($this->_params['tenantID'] == $this->authUser->tenantID){
				
				$creator = new IntegrityInvoice\Api\Item\Creator($this);
						
				$myprice = str_replace('', '&', $this->_params['unit_price']);
				$myprice = str_replace('', '$', $this->_params['unit_price']);
				$myprice = str_replace('', '£', $this->_params['unit_price']);
				$myprice = str_replace('', '%', $this->_params['unit_price']);
				$myprice = str_replace('', '*', $this->_params['unit_price']);
				$myprice = str_replace('', '€', $this->_params['unit_price']);
				
				return $creator->create(array(
					'item_name' => $this->_params['item_name'],
					'item_type' => $this->_params['item_type'],
					'tax_type' => $this->_params['tax_type'],
					'unit_price' => $myprice,
					'created_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
					'updated_at' => strftime("%Y-%m-%d %H:%M:%S", time()),
					'tenantID' => $this->_params['tenantID']
				));
				
			}
			else
			{
				return Response::json('Unknown Account!');
			}	

		}
		else
		{
			return Response::json('Invalid Credenitial!');
		}
   }


	public function itemCreationFails($errors){
			
		return  $errors;
	}
	
	public function itemCreationSucceeds($item){
		
		return $item;
	}
	

   public function readAllItems()
   {
      if(Auth::attempt(array('email' => $this->_params['email'], 'password' => $this->_params['password'])))
		{
			$this->authUser = Auth::user();
			
			if($this->_params['tenantID'] == $this->authUser->tenantID){
				
				$reader = new IntegrityInvoice\Api\Item\Reader();
				return $reader->readAll($this->_params['tenantID']);
				
			}
			else
			{
				return "Unknown Account!";
			}	

		}
		else
		{
			return 'Invalid Credenitial!';
		}
   }
   

   public function readItem()
   {
      if(Auth::attempt(array('email' => $this->_params['username'], 'password' => $this->_params['password'])))
		{
			$this->authUser = Auth::user();
			
			if($this->_params['tenantID'] == $this->authUser->tenantID){
				
				$reader = new IntegrityInvoice\Api\Item\Reader();
				return $reader->read($this->_params['tenantID'], $this->_params['id']);
			}
			else
			{
				return "Unknown Account!";
			}	

		}
		else
		{
			return 'Invalid Credenitial!';
		}
   }
   
   
    
   public function updateItem()
   {
       
   }
    

	public function removeItem()
	{
		
	   if(Auth::attempt(array('email' => $this->_params['email'], 'password' => $this->_params['password'])))
		{
			$this->authUser = Auth::user();
			
			if($this->_params['tenantID'] == $this->authUser->tenantID){
				
				$remover = new IntegrityInvoice\Api\Item\Remover();
				return $remover->remove($this->_params['tenantID'], $this->_params['id']);
			}
			else
			{
				return "Unknown Account!";
			}	

		}
		else
		{
			return 'Invalid Credenitial!';
		}
		
	}

}
