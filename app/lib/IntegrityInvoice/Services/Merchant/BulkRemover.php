<?php namespace IntegrityInvoice\Services\Merchant;

class BulkRemover {
 
	protected $listener;
	protected $merchant;
	
	public function __construct($merchant, $listener)
    {
		$this->listener = $listener;
		$this->merchant = $merchant;
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
			$affectedRows = $this->merchant->remove($this->listener->tenantID, $id);	
		}
		
		if(!is_numeric($affectedRows) || $affectedRows < 1)
		{
			return $this->listener->merchantBulkDeletionFails();
		}
			
		return $this->listener->merchantBulkDeletionSucceeds();
  
	}
	
}