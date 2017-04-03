<?php namespace IntegrityInvoice\Services\CompanyDetails;
use IntegrityInvoice\Services\Validation\CompanyDetailsValidator as Validator;

use Company;

class Creator {
	
	private $listener;
	protected $validator;
 
	public function __construct($listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
	}
	
	public function create($input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->companyDetailsCreationFails($this->validator->errors());
	 
		} 
			
		$companyDetails = Company::create($input);		
		// Note: May ClientEvent::create later 
		
		return $this->listener->companyDetailsCreationSucceeds();
		
	}
	
}