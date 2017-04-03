<?php namespace IntegrityInvoice\Services\CompanyDetails;

use IntegrityInvoice\Services\Validation\CompanyDetailsValidator as Validator;
 
class Updater {
	
	private $listener;
	protected $validator;
	protected $company;
 
	public function __construct($company, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->company = $company;
	}
	
	public function update($input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->companyDetailsUpdateFails($this->validator->errors());
	 
		} 
			
		$this->company->update($this->listener->tenantID, $input);
		
		return $this->listener->companyDetailsUpdateSucceeds();
		
	}
	public function updateFromadmin($tenantID,$input)
	{
					
		return $this->company->update($tenantID, $input);
		
		
	}
	
	public function updateBillingId($input)
	{
		if(!$input)
		{
			return false;
		} 
			
		return $this->company->update($this->listener->tenantID, $input);
		 
	}
	
}
 
