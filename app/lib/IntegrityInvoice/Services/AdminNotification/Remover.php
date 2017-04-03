<?php namespace IntegrityInvoice\Services\AdminNotification;

class Remover {
 
	protected $listener;
	protected $notification;
	
	public function __construct($notification, $listener)
    {
		$this->listener = $listener;
		$this->notification = $notification;
	}
	
	public function remove($id)
	{
		
		if(! is_numeric($id))
		{
			return $this->listener->notificationDeletionFails();	 
		}
		
		$affectedRows = $this->notification->remove($id);
		
		if(!is_numeric($affectedRows) || $affectedRows < 1){
			return $this->listener->notificationDeletionFails();
		}
		
		// Note: May ExpenseEvent::create later 		
		return $this->listener->notificationDeletionSucceeds();
  
	}
	
	public function removeAll()
	{
		$this->notification->removeAll();
	}
	
}