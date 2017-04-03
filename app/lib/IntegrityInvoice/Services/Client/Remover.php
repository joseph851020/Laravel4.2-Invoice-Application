<?php namespace IntegrityInvoice\Services\Client;

class Remover {
 
	protected $listener;
	protected $client;
	
	public function __construct($client, $listener)
    {
		$this->listener = $listener;
		$this->client = $client;
	}
	
	public function remove($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->clientDeletionFails();	 
		}
		
		$affectedRows = $this->client->remove($this->listener->tenantID, $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->clientDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->clientDeletionSucceeds();
  
	}
	
	public function removeAll()
	{
		$this->client->removeAll($this->listener->tenantID);
	}
	
}