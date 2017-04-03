<?php namespace IntegrityInvoice\Services\Item;

class BulkRemover {
 
	protected $listener;
	protected $item;
	
	public function __construct($item, $listener)
    {
		$this->listener = $listener;
		$this->item = $item;
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
			$affectedRows = $this->item->remove($this->listener->tenantID, $id);	
		}
		
		if(!is_numeric($affectedRows) || $affectedRows < 1)
		{
			return $this->listener->itemBulkDeletionFails();
		}
			
		return $this->listener->itemBulkDeletionSucceeds();
  
	}
	
}