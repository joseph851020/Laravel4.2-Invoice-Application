<?php namespace IntegrityInvoice\Services\Preference;

class Remover {
 
	protected $listener;
	protected $preference;
	
	public function __construct($preference, $listener)
    {
		$this->listener = $listener;
		$this->preference = $preference;
	}
	
	public function remove()
	{
		 
		if($this->listener->tenantID == "" || $this->listener->tenantID == null)
		{
			return $this->listener->preferenceDeletionFails();	 
		}
		
		$affectedRows = $this->preference->remove($this->listener->tenantID);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->preferenceDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->preferenceDeletionSucceeds();
  
	}
 
}