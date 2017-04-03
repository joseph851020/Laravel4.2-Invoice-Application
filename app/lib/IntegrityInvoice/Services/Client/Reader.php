<?php namespace IntegrityInvoice\Services\Client;

class Reader {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbItemRepository
	 */
	
	protected $listener;
	protected $client;
 	
	public function __construct($client, $listener)
    {
    	$this->listener = $listener;
		$this->client = $client;
	}
	
	public function read($id)
	{ 
		if(! is_numeric($id))
		return "FAILS";
		
		return $this->client->find($this->listener->tenantID, $id);
	}
	
	
	public function readAll($searchquery = "")
	{
		return $this->client->getAll($this->listener->tenantID, $searchquery, $this->listener->perPage);
	}
	
}