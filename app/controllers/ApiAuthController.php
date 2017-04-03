<?php

class ApiAuthController extends BaseController {
	
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
    
   public function authenticateAccount()
   {
   		
   		$authenticator = new IntegrityInvoice\Api\Account\Authenticator($this);
				
		return $authenticator->authenticate(array(
			'email' => $this->_params['email'],
			'password' => $this->_params['password'],
			'tenantID' => $this->_params['tenantID']
		));
   }


	public function authCreationFails($errors){
	
		return $response = array('response' => 'FAILS', 'data' => $errors);
	}
	
	public function authCreationSucceeds($user){
		
				
		return $response = array('response' => 'SUCCEEDS', 'data' =>  json_decode($user));
	}
	

}
