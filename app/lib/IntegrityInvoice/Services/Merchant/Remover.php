<?php namespace IntegrityInvoice\Services\Merchant;

class Remover {
 
	protected $listener;
	protected $merchant;
	
	public function __construct($merchant, $listener)
    {
		$this->listener = $listener;
		$this->merchant = $merchant;
	}
	
	public function remove($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->merchantDeletionFails();	 
		}
		
		$affectedRows = $this->merchant->remove($this->listener->tenantID, $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->merchantDeletionFails();
		}
 	
		return $this->listener->merchantDeletionSucceeds();
  
	}
	
	public function removeAll()
	{
		$this->merchant->removeAll($this->listener->tenantID);
	}
	
}