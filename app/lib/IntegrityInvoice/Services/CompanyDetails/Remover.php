<?php namespace IntegrityInvoice\Services\CompanyDetails;

class Remover {
 
	protected $listener;
	protected $company;
	
	public function __construct($company, $listener)
    {
		$this->listener = $listener;
		$this->company = $company;
	}
	
	public function remove()
	{
		
		if($this->listener->tenantID == "" || $this->listener->tenantID == null)
		{
			return $this->listener->companyDetailsDeletionFails();	 
		}
		
		$affectedRows = $this->company->remove($this->listener->tenantID);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->companyDetailsDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->companyDetailsDeletionSucceeds();
  
	}
	
}