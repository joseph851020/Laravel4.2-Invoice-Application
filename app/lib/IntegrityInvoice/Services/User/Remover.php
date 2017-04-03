<?php namespace IntegrityInvoice\Services\User;

class Remover {
 
	protected $listener;
	protected $user;
	
	public function __construct($user, $listener)
    {
		$this->listener = $listener;
		$this->user = $user;
	}
	
	public function remove($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->userDeletionFails();	 
		}
		
		$affectedRows = $this->user->remove($this->listener->tenantID, $id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->userDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->userDeletionSucceeds();
  
	}
	
	public function removeAll()
	{
		$this->user->removeAll($this->listener->tenantID);
	}
	
}