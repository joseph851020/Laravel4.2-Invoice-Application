<?php namespace IntegrityInvoice\Services\Item;

class Remover {
 
	protected $listener;
	protected $item;
	
	public function __construct($item, $listener)
    {
		$this->listener = $listener;
		$this->item = $item;
	}
	
	public function remove($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->itemDeletionFails();	 
		}
		
		$affectedRows = $this->item->remove($this->listener->tenantID, $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->itemDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->itemDeletionSucceeds();
  
	}
	
	public function removeAll()
	{
		$this->item->removeAll($this->listener->tenantID);
	}
	
}