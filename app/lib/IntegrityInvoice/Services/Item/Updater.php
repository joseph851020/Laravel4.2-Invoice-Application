<?php namespace IntegrityInvoice\Services\Item;

use IntegrityInvoice\Services\Validation\ItemValidator as Validator;

class Updater {
	
	protected $validator;
	protected $listener;
	protected $item;
	
	public function __construct($item, $listener)
    {
    	$this->validator = new Validator;
		$this->listener = $listener;
		$this->item = $item;
	}
	
	public function update($id, $input)
	{
		 
		if(! $this->validator->validate($input))
		{
			return $this->listener->itemUpdateFails($id, $this->validator->errors());
	 
		} 
			
		$this->item->update($this->listener->tenantID, $id, $input);
		
		// Note: May ClientEvent::create later 
		
		return $this->listener->itemUpdateSucceeds($id);
  
	}
	
}
