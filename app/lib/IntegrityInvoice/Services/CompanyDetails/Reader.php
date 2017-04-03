<?php namespace IntegrityInvoice\Services\CompanyDetails;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbItemRepository
	 */
	
	protected $listener;
	protected $company;
 	
	public function __construct($company, $listener)
    {
    	$this->listener = $listener;
		$this->company = $company;
	}
	
	public function read()
	{  
		return $this->company->find($this->listener->tenantID);
	}
 
}