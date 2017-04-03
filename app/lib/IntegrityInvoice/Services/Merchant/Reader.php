<?php namespace IntegrityInvoice\Services\Merchant;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbVendorRepository
	 */
	
	protected $listener;
	protected $merchant;
 	
	public function __construct($merchant, $listener)
    {
    	$this->listener = $listener;
		$this->merchant = $merchant;
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->merchant->find($this->listener->tenantID, $id);
	}
	
	
	public function readAll($searchquery = "")
	{
		return $this->merchant->getAll($this->listener->tenantID, $searchquery, $this->listener->perPage);
	}
	
}