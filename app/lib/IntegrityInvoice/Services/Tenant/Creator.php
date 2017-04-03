<?php namespace IntegrityInvoice\Services\Tenant;

use IntegrityInvoice\Services\Validation\TenantValidator as Validator;

class Creator {
	
	protected $validator;
	protected $listener;
	protected $tenant;
	
	public function __construct($tenant, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->tenant = $tenant;
	}
	
	public function create($input)
	{
				
		if(! $this->validator->validate($input))
		{
			return $this->listener->tenantCreationFails($this->validator->errors());	 
		}
		
		/*
		// Captcha verification
		if($input['captcha'] != '3nc9z')
		{ return $this->listener->tenantCreationFails(array('captcha' => 'Incorrect captcha')); }
		
		*/	
		
		// Remove items not required	
		$required_input = array_except($input, array('email', 'password'));
		
		$this->tenant->create($required_input);
		// Note: May SignupEvent::create later 
		
		return $this->listener->tenantCreationSucceeds();
  
	}
	
	public function checkReferralCodeExists($code = ""){
	  
		if($referral_tenant = $this->tenant->checkReferral($code)){
			return $referral_tenant;
		}
		
		return false;
	}
	
}