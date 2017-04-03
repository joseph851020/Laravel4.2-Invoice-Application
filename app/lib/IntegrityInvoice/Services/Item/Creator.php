<?php namespace IntegrityInvoice\Services\Item;

use IntegrityInvoice\Services\Validation\ItemValidator as Validator;

class Creator {
	
	/**
	 *  @var IntegrityInvoice\Repositories\DbItemRepository
	 */
	
	protected $validator;
	protected $listener;
	protected $item;
	
	public function __construct($item, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->item = $item;
	}
	
	public function create($input, $redirect = TRUE)
	{
		 
		if(! $this->validator->validate($input))
		{
			return $this->listener->itemCreationFails($this->validator->errors());
	 
		} 
		
		$this->item->create($input);

		// Note: May ClientEvent::create later 
		if($redirect == TRUE || $redirect == NULL)
		{
			return $this->listener->itemCreationSucceeds();
		}
  
	}
	
}
