<?php namespace IntegrityInvoice\Api\Item;

use IntegrityInvoice\Services\Validation\ItemValidator as Validator;
use Item;

class Creator {
	
	protected $validator;
	protected $listener;
	
	public function __construct($listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
	}
	
	public function create($input)
	{
		
		if(! $this->validator->validate($input))
		{
			return $this->listener->itemCreationFails($this->validator->errors());
	 
		} 
			
		$item = Item::create($input);
		
		// Note: May ClientEvent::create later 
		
		return $this->listener->itemCreationSucceeds($item);
  
	}
	
}