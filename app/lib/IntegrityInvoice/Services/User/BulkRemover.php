<?php namespace IntegrityInvoice\Services\Client;

class BulkRemover {
 
	protected $listener;
	protected $client;
	
	public function __construct($client, $listener)
    {
		$this->listener = $listener;
		$this->client = $client;
	}
	
	public function remove($input)
	{
			
		$count = count($input);
		
		if($count == 0 || is_null($count))
		{
			return $this->listener->itemDeletionFails();
		}
		
		for($i=0; $i<$count; $i++)
		{
			$id = $input[$i];
			$affectedRows = $this->client->remove($this->listener->tenantID, $id);	
		}
		
		if(!is_numeric($affectedRows) || $affectedRows < 1)
		{
			return $this->listener->clientBulkDeletionFails();
		}
			
		return $this->listener->clientBulkDeletionSucceeds();
  
	}
	
}