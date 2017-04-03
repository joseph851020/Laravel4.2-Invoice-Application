<?php namespace IntegrityInvoice\Services\CancelQueue;

use IntegrityInvoice\Services\Validation\CancelQueueValidator as Validator;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbcancelQueueRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $cancelQueue;
	
	public function __construct($cancelQueue, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->cancelQueue = $cancelQueue;
	}
	
	public function create($input, $redirect = TRUE)
	{
		
		if(! $this->validator->validate($input))
		{
			return $this->listener->cancelQueueCreationFails($this->validator->errors());
	 
		} 
			
		$cancelQueue = $this->cancelQueue->create($input);
		
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->cancelQueueCreationSucceeds();
		}
  
	}
	
}