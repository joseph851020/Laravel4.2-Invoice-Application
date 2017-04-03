<?php namespace IntegrityInvoice\Services\Client;

use IntegrityInvoice\Services\Validation\ClientValidator as Validator;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbClientRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $client;
	
	public function __construct($client, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->client = $client;
	}
	
	public function create($input, $redirect = TRUE)
	{
		
		if(! $this->validator->validate($input))
		{
			return $this->listener->clientCreationFails($this->validator->errors());
	 
		} 
			
		$client = $this->client->create($input);
		
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->clientCreationSucceeds();
		}
  
	}
	
}