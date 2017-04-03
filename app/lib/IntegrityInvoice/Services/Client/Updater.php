<?php namespace IntegrityInvoice\Services\Client;

use IntegrityInvoice\Services\Validation\ClientValidator as Validator;
 
class Updater {
	
	private $listener;
	protected $validator;
	protected $client;
 
	public function __construct($client, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->client = $client;
	}
	
	public function update($id, $input)
	{
		if(! $this->validator->validate($input))
		{
			return $this->listener->clientUpdateFails($id, $this->validator->errors());
	 
		} 
			
		$this->client->update($this->listener->tenantID, $id, $input);
		
		return $this->listener->clientUpdateSucceeds($id);
		
	}
	
}
 
