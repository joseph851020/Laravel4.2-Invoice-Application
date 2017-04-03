<?php namespace IntegrityInvoice\Api\Account;

use IntegrityInvoice\Services\Validation\AuthValidator as Validator;
use Auth;

class Authenticator {
		
	protected $validator;
	protected $listener;
	protected $user;
	
	public function __construct($listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
	}
	
	public function authenticate($input)
	{
		
		if(! $this->validator->validate($input))
		{
			return $this->listener->authCreationFails($this->validator->errors());
	 
		}
		
		if(Auth::attempt(array('email' => $input['email'], 'password' => $input['password'])))
		{
			
			if($input['tenantID'] == Auth::user()->tenantID)
			{
				$this->user = Auth::user();
				
				return $this->listener->authCreationSucceeds($this->user);
			}
			else
			{
			     return  $this->listener->authCreationFails("Invalid Account ID");
			}
		}
		else
		{
			return $this->listener->authCreationFails("Invalid Email / Password");
		}

  
	}
		
		
}
	