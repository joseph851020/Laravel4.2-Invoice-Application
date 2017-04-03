<?php namespace IntegrityInvoice\Services\CompanyDetails;
 
class CompanyOntimeUpdater {
	
	private $listener;
	protected $company;
 
	public function __construct($company, $listener)
    {
 
		$this->listener = $listener;
		$this->company = $company;
	}
	
	public function update($input)
	{
		if(!$input)
		{
			return false;
		} 
			
		return $this->company->update($this->listener->tenantID, $input);
		
	}
 
}
 
