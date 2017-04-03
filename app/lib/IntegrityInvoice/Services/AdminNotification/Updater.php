<?php namespace IntegrityInvoice\Services\AdminNotification;
 
class Updater {
	
	protected $listener;
	protected $notification;
	
	public function __construct($notification, $listener)
    {
		$this->listener = $listener;
		$this->notification = $notification;
	}
	
	public function update($id, $input)
	{	  	
		$this->notification->update($id, $input);	  
		return $this->listener->notificationUpdateSucceeds($id);  
	}
	
}
