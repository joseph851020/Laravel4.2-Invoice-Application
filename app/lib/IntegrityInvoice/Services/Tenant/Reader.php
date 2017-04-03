<?php namespace IntegrityInvoice\Services\Tenant;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbTenantRepository
	 */
	
	protected $listener;
	protected $tenant;
 	
	public function __construct($tenant, $listener)
    {
    	$this->listener = $listener;
		$this->tenant = $tenant;
	}
	
	public function read($tenantID)
	{ 
		if($tenantID == "" || $tenantID ==  NULL){
			return Redirect::to('admin/accounts')->with('failed_flash_message', 'Invalid Tenant ID');
		}
		
		return $this->tenant->find($tenantID);
	}
	
	
	public function readAll($searchquery = "")
	{
		return $this->tenant->getAll($searchquery, $this->listener->perPage);
	}
	
}