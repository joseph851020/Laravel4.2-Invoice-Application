<?php namespace IntegrityInvoice\Services\User;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbUserRepository
	 */
	
	protected $listener;
	protected $user;
 	
	public function __construct($user, $listener)
    {
    	$this->listener = $listener;
		$this->user = $user;
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->user->find($this->listener->tenantID, $id);
	}
	
	
	public function readAll($searchquery = "")
	{
		return $this->user->getAll($this->listener->tenantID, $searchquery, $this->listener->perPage);
	}
	
}